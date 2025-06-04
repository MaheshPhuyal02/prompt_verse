<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the purchases for the user.
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Get active purchases for the user.
     */
    public function activePurchases(): HasMany
    {
        return $this->purchases()->where('status', 'active');
    }

    /**
     * Get total amount spent by the user.
     */
    public function getTotalSpentAttribute(): float
    {
        return $this->activePurchases()->sum('price');
    }

    /**
     * Get purchase count for the user.
     */
    public function getPurchaseCountAttribute(): int
    {
        return $this->activePurchases()->count();
    }
}
