<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnitConfigurationValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'configuration_field_id',
        'value',
    ];

    /**
     * The unit that owns this configuration value.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * The configuration field this value is for.
     */
    public function field(): BelongsTo
    {
        return $this->belongsTo(ConfigurationField::class, 'configuration_field_id');
    }
}
