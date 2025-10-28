<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConfigurationProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Fields that belong to the configuration profile.
     */
    public function fields(): HasMany
    {
        return $this->hasMany(ConfigurationField::class);
    }

    /**
     * Units that use this configuration profile.
     */
    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }
}
