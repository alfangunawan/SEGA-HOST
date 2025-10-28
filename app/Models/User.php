<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo',
        'role',
        'balance',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'balance' => 'decimal:2',
        ];
    }

    /**
     * Rentals made by the user.
     */
    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    /**
     * Get active rentals count
     */
    public function activeRentalsCount(): int
    {
        return $this->rentals()->whereIn('status', ['pending', 'active'])->count();
    }

    /**
     * Check if user can rent more servers
     */
    public function canRentMoreServers(): bool
    {
        return $this->activeRentalsCount() < 2; // Maksimal 2 server
    }

    /**
     * Get remaining rental slots
     */
    public function remainingRentalSlots(): int
    {
        return max(0, 2 - $this->activeRentalsCount());
    }

    public function getProfilePhotoUrlAttribute(): ?string
    {
        return $this->profile_photo
            ? asset('storage/' . $this->profile_photo)
            : null;
    }

    /**
     * Determine whether the user has enough balance for a given amount.
     */
    public function hasSufficientBalance(float $amount): bool
    {
        if ($amount <= 0) {
            return true;
        }

        return round(((float) $this->balance) - $amount, 2) >= 0;
    }

    /**
     * Adjust the user's balance by the given amount. Must be called inside a transaction.
     */
    public function adjustBalance(float $amount): void
    {
        if (abs($amount) < 0.00001) {
            return;
        }

        $lockedUser = static::query()
            ->whereKey($this->getKey())
            ->lockForUpdate()
            ->first();

        if (!$lockedUser) {
            return;
        }

        $newBalance = round(((float) $lockedUser->balance) + $amount, 2);

        $lockedUser->forceFill([
            'balance' => $newBalance,
        ])->save();

        $this->balance = $lockedUser->balance;
    }

    /**
     * Reduce the user's balance by the given amount. Must be called inside a transaction.
     */
    public function deductBalance(float $amount): void
    {
        if ($amount <= 0) {
            return;
        }

        $this->adjustBalance($amount * -1);
    }

    /**
     * Increase the user's balance by the given amount. Must be called inside a transaction.
     */
    public function creditBalance(float $amount): void
    {
        if ($amount <= 0) {
            return;
        }

        $this->adjustBalance($amount);
    }
}
