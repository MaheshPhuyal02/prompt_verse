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
        'purchase_price',
        'purchase_date',
        'prompt_snapshot',
        'status',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'purchase_date' => 'datetime',
        'prompt_snapshot' => 'array',
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
        return $query->whereBetween('purchase_date', [$startDate, $endDate]);
    }

    /**
     * Get formatted purchase date for API response.
     */
    public function getFormattedPurchaseDateAttribute(): string
    {
        return $this->purchase_date->format('M d, Y');
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
                'rating' => $this->prompt->rating,
                'popular' => $this->prompt->popular,
            ];
        }

        // Fallback to snapshot if prompt is deleted
        return $this->prompt_snapshot ?? [];
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
