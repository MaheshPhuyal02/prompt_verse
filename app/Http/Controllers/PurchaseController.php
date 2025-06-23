<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the user's purchases.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Auth::user()->purchases()->active()->with('prompt');

        // Filter by category if provided
        if ($request->has('category') && $request->category !== '') {
            $query->byCategory($request->category);
        }

        // Filter by date range if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->betweenDates($request->start_date, $request->end_date);
        }

        // Sort by purchase date (newest first by default)
        $sortOrder = $request->get('sort', 'desc');
        $query->orderBy('purchase_date', $sortOrder);

        $purchases = $query->paginate($request->get('per_page', 15));

        // Transform the data to match your frontend structure
        $transformedPurchases = $purchases->getCollection()->map(function ($purchase) {
            $promptData = $purchase->getPromptData();

            return [
                'id' => $purchase->id,
                'title' => $promptData['title'] ?? 'Unknown Prompt',
                'description' => $promptData['description'] ?? '',
                'price' => $purchase->purchase_price,
                'category' => $promptData['category'] ?? 'Unknown',
                'purchaseDate' => $purchase->formatted_purchase_date,
                'status' => $purchase->status,
                'prompt_id' => $purchase->prompt_id,
                'image' => $promptData['image'] ?? null,
                'rating' => $promptData['rating'] ?? null,
                'current_price' => $promptData['current_price'] ?? null,
                'created_at' => $purchase->created_at,
                'updated_at' => $purchase->updated_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $transformedPurchases,
            'meta' => [
                'current_page' => $purchases->currentPage(),
                'per_page' => $purchases->perPage(),
                'total' => $purchases->total(),
                'last_page' => $purchases->lastPage(),
            ]
        ]);
    }

    /**
     * Store a newly created purchase.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'prompt_id' => 'required|exists:prompts,id',
            ]);

            // Check if user already owns this prompt
            $existingPurchase = Auth::user()->purchases()
                ->where('prompt_id', $validated['prompt_id'])
                ->where('status', 'active')
                ->first();

            if ($existingPurchase) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already own this prompt'
                ], 422);
            }

            // Get the prompt
            $prompt = \App\Models\Prompt::findOrFail($validated['prompt_id']);

            // Create snapshot of prompt data at time of purchase
            $promptSnapshot = [
                'title' => $prompt->title,
                'description' => $prompt->description,
                'category' => $prompt->category,
                'image' => $prompt->image,
                'rating' => $prompt->rating,
                'popular' => $prompt->popular,
            ];

            $purchase = Auth::user()->purchases()->create([
                'prompt_id' => $prompt->id,
                'purchase_price' => $prompt->price,
                'prompt_snapshot' => $promptSnapshot,
                'purchase_date' => now(),
                'status' => 'active',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Purchase completed successfully',
                'data' => [
                    'id' => $purchase->id,
                    'title' => $prompt->title,
                    'description' => $prompt->description,
                    'price' => $purchase->purchase_price,
                    'category' => $prompt->category,
                    'purchaseDate' => $purchase->formatted_purchase_date,
                    'status' => $purchase->status,
                    'prompt_id' => $purchase->prompt_id,
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Display the specified purchase.
     */
    public function show(Purchase $purchase): JsonResponse
    {
        // Ensure user can only access their own purchases
        if ($purchase->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Purchase not found'
            ], 404);
        }

        $purchase->load('prompt');
        $promptData = $purchase->getPromptData();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $purchase->id,
                'title' => $promptData['title'] ?? 'Unknown Prompt',
                'description' => $promptData['description'] ?? '',
                'price' => $purchase->purchase_price,
                'category' => $promptData['category'] ?? 'Unknown',
                'purchaseDate' => $purchase->formatted_purchase_date,
                'status' => $purchase->status,
                'prompt_id' => $purchase->prompt_id,
                'image' => $promptData['image'] ?? null,
                'rating' => $promptData['rating'] ?? null,
                'current_price' => $promptData['current_price'] ?? null,
                'prompt_snapshot' => $purchase->getPromptSnapshot(),
                'created_at' => $purchase->created_at,
                'updated_at' => $purchase->updated_at,
            ]
        ]);
    }

    /**
     * Update the specified purchase.
     */
    public function update(Request $request, Purchase $purchase): JsonResponse
    {
        // Ensure user can only update their own purchases
        if ($purchase->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Purchase not found'
            ], 404);
        }

        try {
            $validated = $request->validate([
                'status' => 'sometimes|in:active,inactive,refunded',
            ]);

            $purchase->update($validated);
            $promptData = $purchase->getPromptData();

            return response()->json([
                'success' => true,
                'message' => 'Purchase updated successfully',
                'data' => [
                    'id' => $purchase->id,
                    'title' => $promptData['title'] ?? 'Unknown Prompt',
                    'description' => $promptData['description'] ?? '',
                    'price' => $purchase->purchase_price,
                    'category' => $promptData['category'] ?? 'Unknown',
                    'purchaseDate' => $purchase->formatted_purchase_date,
                    'status' => $purchase->status,
                    'prompt_id' => $purchase->prompt_id,
                ]
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Remove the specified purchase.
     */
    public function destroy(Purchase $purchase): JsonResponse
    {
        // Ensure user can only delete their own purchases
        if ($purchase->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Purchase not found'
            ], 404);
        }

        $purchase->delete();

        return response()->json([
            'success' => true,
            'message' => 'Purchase deleted successfully'
        ]);
    }

    /**
     * Get purchase categories for the authenticated user.
     */
    public function categories(): JsonResponse
    {
        $categories = Auth::user()->purchases()
            ->active()
            ->with('prompt')
            ->get()
            ->pluck('prompt.category')
            ->filter()
            ->unique()
            ->values();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Get purchase statistics for the authenticated user.
     */
    public function stats(): JsonResponse
    {
        $purchases = Auth::user()->purchases()->active()->with('prompt');

        $stats = [
            'total_purchases' => $purchases->count(),
            'total_spent' => $purchases->sum('purchase_price'),
            'categories_count' => $purchases->get()->pluck('prompt.category')->filter()->unique()->count(),
            'recent_purchases' => $purchases->orderBy('purchase_date', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($purchase) {
                    $promptData = $purchase->getPromptData();
                    return [
                        'id' => $purchase->id,
                        'title' => $promptData['title'] ?? 'Unknown Prompt',
                        'price' => $purchase->purchase_price,
                        'category' => $promptData['category'] ?? 'Unknown',
                        'purchaseDate' => $purchase->formatted_purchase_date,
                    ];
                })
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get all purchases for the admin panel.
     */
    public function all(Request $request): JsonResponse
    {
        // In a real app, you'd want to add authorization to ensure only admins can access this.
        // For example: if ($request->user()->cannot('view-all-purchases')) {
        //   abort(403);
        // }

        $purchases = Purchase::with(['user', 'prompt'])->latest()->get();

        $transformedPurchases = $purchases->map(function ($purchase) {
            $promptData = $purchase->prompt ? [
                'title' => $purchase->prompt->title,
                'description' => $purchase->prompt->description,
            ] : ($purchase->prompt_snapshot ?? ['title' => 'Unknown Prompt', 'description' => '']);

            return [
                'id' => $purchase->id,
                'user' => $purchase->user ? $purchase->user->name : 'Unknown User',
                'item' => $promptData['title'],
                'amount' => $purchase->price_at_time,
                'status' => $purchase->status,
                'date' => $purchase->purchased_at ? $purchase->purchased_at->toFormattedDateString() : 'N/A',
            ];
        });

        return response()->json($transformedPurchases);
    }
}
