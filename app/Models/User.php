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
}
