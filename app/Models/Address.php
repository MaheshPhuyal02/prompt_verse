<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'province',
        'district',
        'municipality',
        'ward',
        'street_address',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'ward' => 'integer',
    ];

    /**
     * Get the user that owns the address.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the full name of the address owner.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get the complete address as a string.
     */
    public function getCompleteAddressAttribute(): string
    {
        return "{$this->street_address}, Ward {$this->ward}, {$this->municipality}, {$this->district}, {$this->province}";
    }
} 