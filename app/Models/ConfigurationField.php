<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConfigurationField extends Model
{
    use HasFactory;

    protected $fillable = [
        'configuration_profile_id',
        'label',
        'key',
        'type',
        'options',
        'is_required',
        'meta',
    ];

    protected $casts = [
        'options' => 'array',
        'meta' => 'array',
        'is_required' => 'boolean',
    ];

    /**
     * The profile this field belongs to.
     */
    public function profile(): BelongsTo
    {
        return $this->belongsTo(ConfigurationProfile::class, 'configuration_profile_id');
    }

    /**
     * Stored values for this configuration field.
     */
    public function unitValues(): HasMany
    {
        return $this->hasMany(UnitConfigurationValue::class);
    }
}
