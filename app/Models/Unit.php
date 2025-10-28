<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'price_per_day',
        'penalty',
        'ip_address',
        'location',
        'configuration_profile_id',
    ];

    protected $casts = [
        'price_per_day' => 'decimal:2',
        'penalty' => 'integer',
    ];

    /**
     * Categories associated with the unit.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_unit')->withTimestamps();
    }

    /**
     * Get the rentals for the unit.
     */
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    /**
     * Get current active rental
     */
    public function currentRental()
    {
        return $this->hasOne(Rental::class)->where('status', 'active');
    }

    /**
     * Configuration profile selected for this unit.
     */
    public function configurationProfile(): BelongsTo
    {
        return $this->belongsTo(ConfigurationProfile::class);
    }

    /**
     * Stored configuration values keyed by field.
     */
    public function configurationValues(): HasMany
    {
        return $this->hasMany(UnitConfigurationValue::class);
    }
}
