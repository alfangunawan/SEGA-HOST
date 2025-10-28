<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Rental extends Model
{
    use HasFactory;

    public const STATUS_DEFAULT = 'pending';

    public const STATUS_LABELS = [
        'pending' => 'Menunggu Konfirmasi',
        'active' => 'Sedang Berjalan',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
        'overdue' => 'Terlambat',
        'returned_early' => 'Dikembalikan Lebih Awal',
    ];

    public const STATUS_BADGE_CLASSES = [
        'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-200',
        'active' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-200',
        'completed' => 'bg-sky-100 text-sky-700 dark:bg-sky-500/20 dark:text-sky-200',
        'cancelled' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-200',
        'overdue' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',
        'returned_early' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-200',
    ];

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

    public static function availableStatuses(): array
    {
        return array_keys(self::STATUS_LABELS);
    }

    public static function statusLabel(?string $status): string
    {
        $status = $status ?? self::STATUS_DEFAULT;

        $label = self::STATUS_LABELS[$status] ?? Str::title(str_replace('_', ' ', (string) $status));

        return __($label);
    }

    public static function statusBadgeClasses(?string $status): string
    {
        return self::STATUS_BADGE_CLASSES[$status] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300';
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
