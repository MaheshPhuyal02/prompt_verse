<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Prompt;
use App\Models\Purchase;
use App\Models\UserPrompt;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;

class CartController extends Controller
{
    /**
     * Get all cart items for authenticated user.
     */
    public function index(): JsonResponse
    {
        $cartItems = Cart::with(['prompt'])
            ->forUser(Auth::id())
            ->orderBy('added_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'quantity' => $item->quantity,
                    'price_at_time' => $item->price_at_time,
                    'total_price' => $item->total_price,
                    'added_at' => $item->formatted_added_date,
                    'price_changed' => $item->hasPriceChanged(),
                    'prompt' => $item->getPromptData(),
                ];
            });

        // Create a unique cart ID for this session
        $cartId = 'CART_' . Auth::id() . '_' . time();

        $cartSummary = [
            'id' => $cartId,
            'total_items' => $cartItems->sum('quantity'),
            'total_amount' => $cartItems->sum('total_price'),
            'items_count' => $cartItems->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'items' => $cartItems,
                'summary' => $cartSummary,
            ]
        ]);
    }

    /**
     * Add item to cart.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'prompt_id' => 'required|exists:prompts,id',
            'quantity' => 'integer|min:1|max:10',
        ]);

        $prompt = Prompt::findOrFail($request->prompt_id);
        $quantity = $request->quantity ?? 1;

        // Check if item already exists in cart
        $existingItem = Cart::forUser(Auth::id())
            ->where('prompt_id', $request->prompt_id)
            ->first();

        if ($existingItem) {
            // Update quantity and price
            $existingItem->update([
                'quantity' => $existingItem->quantity + $quantity,
                'price_at_time' => $prompt->price, // Update to current price
                'added_at' => now(),
            ]);

            $existingItem->createPromptSnapshot();
            $cartItem = $existingItem;
        } else {
            // Create new cart item
            $cartItem = Cart::create([
                'user_id' => Auth::id(),
                'prompt_id' => $request->prompt_id,
                'quantity' => $quantity,
                'price_at_time' => $prompt->price,
                'added_at' => now(),
            ]);

            $cartItem->createPromptSnapshot();
        }

        $cartItem->load('prompt');

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart successfully',
            'data' => [
                'id' => $cartItem->id,
                'quantity' => $cartItem->quantity,
                'price_at_time' => $cartItem->price_at_time,
                'total_price' => $cartItem->total_price,
                'prompt' => $cartItem->getPromptData(),
            ]
        ], 201);
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, Cart $cart): JsonResponse
    {
        // Ensure user owns this cart item
        if ($cart->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $cart->update([
            'quantity' => $request->quantity,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cart item updated successfully',
            'data' => [
                'id' => $cart->id,
                'quantity' => $cart->quantity,
                'total_price' => $cart->total_price,
            ]
        ]);
    }

    /**
     * Remove item from cart.
     */
    public function destroy(Cart $cart): JsonResponse
    {
        // Ensure user owns this cart item
        if ($cart->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $cart->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart successfully'
        ]);
    }

    /**
     * Update all cart items with current prices.
     */
    public function updatePrices(): JsonResponse
    {
        $cartItems = Cart::with('prompt')
            ->forUser(Auth::id())
            ->get();

        $updatedCount = 0;
        foreach ($cartItems as $item) {
            if ($item->hasPriceChanged()) {
                $item->updatePrice();
                $updatedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Updated prices for {$updatedCount} items",
            'data' => [
                'updated_count' => $updatedCount,
            ]
        ]);
    }

    /**
     * Clear all cart items for authenticated user.
     */
    public function clear(): JsonResponse
    {
        $deletedCount = Cart::forUser(Auth::id())->delete();

        return response()->json([
            'success' => true,
            'message' => "Cleared {$deletedCount} items from cart",
            'data' => [
                'deleted_count' => $deletedCount,
            ]
        ]);
    }

    /**
     * Checkout - convert cart items to purchases.
     */
    public function checkout(): JsonResponse
    {
        $cartItems = Cart::with('prompt')
            ->forUser(Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty'
            ], 400);
        }

        // Check if all prompts still exist
        foreach ($cartItems as $item) {
            if (!$item->prompt) {
                return response()->json([
                    'success' => false,
                    'message' => 'Some items in your cart are no longer available'
                ], 400);
            }
        }

        DB::beginTransaction();
        try {
            $purchases = [];
            $totalAmount = 0;

            foreach ($cartItems as $item) {
                for ($i = 0; $i < $item->quantity; $i++) {
                    $purchase = Purchase::create($item->toPurchaseData());
                    $purchases[] = $purchase;
                    $totalAmount += $item->price_at_time;
                }
            }

            // Clear cart after successful checkout
            Cart::forUser(Auth::id())->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Checkout completed successfully',
                'data' => [
                    'purchases_count' => count($purchases),
                    'total_amount' => $totalAmount,
                    'purchase_ids' => collect($purchases)->pluck('id'),
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Checkout failed. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get cart summary (item count and total).
     */
    public function summary(): JsonResponse
    {
        $cartItems = Cart::forUser(Auth::id())->get();

        $summary = [
            'total_items' => $cartItems->sum('quantity'),
            'total_amount' => $cartItems->sum('total_price'),
            'items_count' => $cartItems->count(),
            'has_price_changes' => $cartItems->some(fn($item) => $item->hasPriceChanged()),
        ];

        return response()->json([
            'success' => true,
            'data' => $summary
        ]);
    }

    /**
     * Generate Khalti payment button for a cart.
     */
    public function getKhaltiButton(Request $request)
    {
        try {
            $user = Auth::user();
            // Get all cart items for the user
            $cartItems = Cart::with(['prompt'])
                ->forUser(Auth::id())
                ->get();

            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is empty'
                ], 400);
            }

            // Calculate total amount
            $totalAmount = $cartItems->sum('total_price') * 100; // Convert to paisa

            // Create purchase order name from cart items
            $purchaseOrderName = $cartItems->count() > 1
                ? "Multiple Prompts Purchase"
                : $cartItems->first()->prompt->title;

            // Prepare product details from cart items
            $productDetails = $cartItems->map(function ($item) {
                return [
                    'identity' => (string)$item->prompt_id,
                    'name' => $item->prompt->title,
                    'total_price' => $item->total_price * 100, // Convert to paisa
                    'quantity' => $item->quantity,
                    'unit_price' => $item->price_at_time * 100 // Convert to paisa
                ];
            })->toArray();

            // Calculate VAT (assuming 13% VAT)
            $vatAmount = round($totalAmount * 0.13);
            $markPrice = $totalAmount - $vatAmount;

            $response = Http::withHeaders([
                'Authorization' => 'key ' . config('services.khalti.secret_key'),
                'Content-Type' => 'application/json',
            ])->post('https://dev.khalti.com/api/v2/epayment/initiate/', [
                'return_url' => 'http://127.0.0.1:8000/api/payment/success',
                'website_url' => config('app.url'),
                'amount' => (string)$totalAmount,
                'purchase_order_id' => $request->cartId ?? 'ORDER_' . time(),
                'purchase_order_name' => $purchaseOrderName,
                'customer_info' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? '9800000000',
                ],
                'amount_breakdown' => [
                    [
                        'label' => 'Mark Price',
                        'amount' => $markPrice
                    ],
                    [
                        'label' => 'VAT',
                        'amount' => $vatAmount
                    ]
                ],
                'product_details' => $productDetails,
                'merchant_extra' =>  Auth::id()
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate payment button',
                'error' => $response->json()
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate payment button',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function handleKhaltiReturn(Request $request)
    {
//        return "GOT REQUEST " . json_encode([
//            'all' => $request->all(),
//            'pidx' => $request->pidx,
//            'transaction_id' => $request->transaction_id,
//            'tidx' => $request->tidx,
//            'txnId' => $request->txnId,
//            'amount' => $request->amount,
//            'total_amount' => $request->total_amount,
//            'mobile' => $request->mobile,
//            'status' => $request->status,
//            'purchase_order_id' => $request->purchase_order_id,
//            'purchase_order_name' => $request->purchase_order_name,
//            'merchant_extra' => $request->merchant_extra
//        ]);

        try {
            // Verify the payment status from Khalti
            $response = Http::withHeaders([
                'Authorization' => 'key ' . config('services.khalti.secret_key'),
                'Content-Type' => 'application/json',
            ])->post('https://dev.khalti.com/api/v2/epayment/lookup/', [
                'pidx' => $request->pidx
            ]);


            if (!$response->successful() || $response->json('status') !== 'Completed') {
                return redirect()->to('http://localhost:8000/payment/failed?message=Payment verification failed');
            }

            $paymentData = $response->json();


            $userId = $request->merchant_extra;

//            return "pidx " . $request->pidx . " userId " . $userId;

            if (!$userId) {
                return redirect()->to('http://localhost:8000/payment/failed?message=Invalid payment data');
            }

//            return "<p>Payment successful! Redirecting...</p>"
//                . json_encode($paymentData);

            if($paymentData["status"] !== 'Completed') {
                return redirect()->to('http://localhost:8000/payment/failed?message=Payment not completed');
            }

            DB::beginTransaction();
            try {
                // Get cart items for the user
                $cartItems = Cart::with('prompt')
                    ->forUser($userId)
                    ->get();

                if ($cartItems->isEmpty()) {
                    throw new \Exception('Cart is empty');
                }

                $purchases = [];
                $totalAmount = 0;
                $userPrompts = [];

                // Create purchases for each cart item
                foreach ($cartItems as $item) {
                    for ($i = 0; $i < $item->quantity; $i++) {
                        // Create purchase record
                        $purchase = Purchase::create([
                            'user_id' => $userId,
                            'price_at_time' => $item->price_at_time,
                            'prompt_id' => $item->prompt_id,
                            'payment_id' => $paymentData['pidx'],
                            'payment_method' => 'khalti',
                            'status' => 'completed',
                            'purchased_at' => now(),
                            'transaction_id' => $paymentData['transaction_id'] ?? null,
                        ]);
                        $purchases[] = $purchase;
                        $totalAmount += $item->price_at_time;
                    }
                }

                Cart::forUser($userId)->delete();

                DB::commit();

                // Redirect to frontend success page with purchase details
                return redirect()->to('http://localhost:8000/payment/success?' . http_build_query([
                    'success' => true,
                    'message' => 'Payment processed successfully',
                    'purchases_count' => count($purchases),
                    'total_amount' => $totalAmount,
                    'payment_id' => $paymentData['pidx']
                ]));

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {

            // log
            Log::error('An exception occurred', ['exception' => $e]);

            return redirect()->to('http://localhost:8000/payment/failed?message=' .
                urlencode($e->getMessage())
                . '&error=' . urlencode($e->getTraceAsString())
            );
        }
    }
}
