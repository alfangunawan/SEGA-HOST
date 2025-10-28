<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryUnit extends Model
{
    use HasFactory;

    protected $table = 'category_unit';

    protected $fillable = [
        'category_id',
        'unit_id',
    ];

    /**
     * Category relationship for the pivot row.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Unit relationship for the pivot row.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
