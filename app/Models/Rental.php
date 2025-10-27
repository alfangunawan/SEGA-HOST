<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'unit_id', 
        'start_date',
        'end_date',
        'status',
        'total_cost',
        'penalty_cost',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_cost' => 'decimal:2',
        'penalty_cost' => 'decimal:2',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_cost' => 'decimal:2',
    ];

    /**
     * Get the user that owns the rental.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the unit being rented.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Check if rental is overdue
     */
    public function isOverdue(): bool
    {
        return $this->status === 'active' && now()->gt($this->end_date);
    }

    /**
     * Calculate days overdue
     */
    public function daysOverdue(): int
    {
        if (!$this->isOverdue()) {
            return 0;
        }
        
        return now()->diffInDays($this->end_date);
    }

    /**
     * Calculate penalty for overdue rental
     */
    public function calculatePenalty(): float
    {
        if (!$this->isOverdue()) {
            return 0;
        }
        
        $daysOverdue = $this->daysOverdue();
        $dailyPenalty = $this->unit->price_per_day * 0.5; // 50% of daily rate
        
        return $daysOverdue * $dailyPenalty;
    }

    /**
     * Check if rental can be returned early
     */
    public function canBeReturnedEarly(): bool
    {
        return $this->status === 'active' && now()->lt($this->end_date);
    }

    /**
     * Calculate potential refund for early return
     */
    public function calculateEarlyReturnRefund(): float
    {
        if (!$this->canBeReturnedEarly()) {
            return 0;
        }
        
        $unusedDays = now()->diffInDays($this->end_date, false);
        if ($unusedDays <= 0) {
            return 0;
        }
        
        // 80% refund for unused days
        return ($this->unit->price_per_day * $unusedDays) * 0.8;
    }
}
