<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Prompt;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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

        $cartSummary = [
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
}
