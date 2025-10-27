<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'status',
        'price_per_day',
        'ip_address',
        'location',
    ];

    /**
     * Categories associated with the unit.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_unit')->withTimestamps();
    }

    /**
     * Rentals associated with the unit.
     */
    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }
}
