<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'prompt_id',
        'quantity',
        'price_at_time',
        'added_at',
        'prompt_snapshot',
    ];

    protected $casts = [
        'price_at_time' => 'decimal:2',
        'added_at' => 'datetime',
        'prompt_snapshot' => 'array',
        'quantity' => 'integer',
    ];

    protected $hidden = [
        'prompt_snapshot', // Hide by default, show only when needed
    ];

    /**
     * Get the user that owns the cart item.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the prompt in the cart.
     */
    public function prompt(): BelongsTo
    {
        return $this->belongsTo(Prompt::class);
    }

    /**
     * Scope a query to only include items for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->whereHas('prompt', function ($q) use ($category) {
            $q->where('category', $category);
        });
    }

    /**
     * Scope a query to get recent cart items.
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('added_at', '>=', Carbon::now()->subDays($days));
    }

    /**
     * Get formatted added date for API response.
     */
    public function getFormattedAddedDateAttribute(): string
    {
        return $this->added_at->format('M d, Y');
    }

    /**
     * Get the total price for this cart item.
     */
    public function getTotalPriceAttribute(): float
    {
        return $this->price_at_time * $this->quantity;
    }

    /**
     * Get the prompt snapshot (only when explicitly requested).
     */
    public function getPromptSnapshot(): ?array
    {
        return $this->prompt_snapshot;
    }

    /**
     * Get current prompt data or fallback to snapshot.
     */
    public function getPromptData(): array
    {
        if ($this->prompt) {
            return [
                'title' => $this->prompt->title,
                'description' => $this->prompt->description,
                'category' => $this->prompt->category,
                'image' => $this->prompt->image,
                'current_price' => $this->prompt->price,
                'cart_price' => $this->price_at_time,
                'rating' => $this->prompt->rating,
                'popular' => $this->prompt->popular,
                'price_changed' => $this->prompt->price != $this->price_at_time,
            ];
        }

        // Fallback to snapshot if prompt is deleted
        return $this->prompt_snapshot ?? [];
    }

    /**
     * Check if the prompt price has changed since adding to cart.
     */
    public function hasPriceChanged(): bool
    {
        return $this->prompt && $this->prompt->price != $this->price_at_time;
    }

    /**
     * Update the price to current prompt price.
     */
    public function updatePrice(): bool
    {
        if ($this->prompt) {
            return $this->update(['price_at_time' => $this->prompt->price]);
        }
        return false;
    }

    /**
     * Create a snapshot of the prompt data.
     */
    public function createPromptSnapshot(): void
    {
        if ($this->prompt) {
            $this->update([
                'prompt_snapshot' => [
                    'title' => $this->prompt->title,
                    'description' => $this->prompt->description,
                    'category' => $this->prompt->category,
                    'image' => $this->prompt->image,
                    'price' => $this->prompt->price,
                    'rating' => $this->prompt->rating,
                    'popular' => $this->prompt->popular,
                    'snapshot_date' => now(),
                ]
            ]);
        }
    }

    /**
     * Convert cart item to purchase data.
     */
    public function toPurchaseData(): array
    {
        return [
            'user_id' => $this->user_id,
            'prompt_id' => $this->prompt_id,
            'purchase_price' => $this->price_at_time,
            'purchase_date' => now(),
            'prompt_snapshot' => $this->getPromptData(),
            'status' => 'active',
        ];
    }
}
