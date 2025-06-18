<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'prompt_id',
        'price_at_time',
        'payment_id',
        'payment_method',
        'status',
        'purchased_at',
        'transaction_id',
    ];

    protected $casts = [
        'price_at_time' => 'decimal:2',
        'purchased_at' => 'datetime',
    ];

    protected $hidden = [
        'prompt_snapshot', // Hide by default, show only when needed
    ];

    /**
     * Get the user that owns the purchase.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the prompt that was purchased.
     */
    public function prompt(): BelongsTo
    {
        return $this->belongsTo(Prompt::class);
    }

    /**
     * Scope a query to only include active purchases.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
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
     * Scope a query to filter by date range.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('purchased_at', [$startDate, $endDate]);
    }

    /**
     * Get formatted purchase date for API response.
     */
    public function getFormattedPurchaseDateAttribute(): string
    {
        return $this->purchased_at->format('M d, Y');
    }

    /**
     * Get the prompt snapshot (only when explicitly requested).
     */
    public function getPromptSnapshot(): ?array
    {
        return $this->prompt_snapshot;
    }

    /**
     * Get current prompt data.
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
                'rating' => $this->prompt->rating,
                'popular' => $this->prompt->popular,
            ];
        }

        return [];
    }

    /**
     * Check if the purchase is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Mark purchase as refunded.
     */
    public function refund(): bool
    {
        return $this->update(['status' => 'refunded']);
    }
}
