<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Units that belong to the category.
     */
    public function units(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class, 'category_unit')->withTimestamps();
    }
}
