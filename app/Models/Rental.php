<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Rental extends Model
{
    use HasFactory;

    // Status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_OVERDUE = 'overdue';

    // Default status
    public const STATUS_DEFAULT = self::STATUS_PENDING;

    // Business constants
    public const MAX_RENTAL_DAYS = 5;
    public const REFUND_PERCENTAGE = 0.8;
    public const PROCESSING_FEE_PERCENTAGE = 0.2;

    // Status labels for display
    public const STATUS_LABELS = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_COMPLETED => 'Completed',
        self::STATUS_OVERDUE => 'Overdue',
    ];

    // Status badge CSS classes
    public const STATUS_BADGE_CLASSES = [
        self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-300',
        self::STATUS_ACTIVE => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-300',
        self::STATUS_COMPLETED => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-300',
        self::STATUS_OVERDUE => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-300',
    ];

    protected $fillable = [
        'user_id',
        'unit_id',
        'start_date',
        'end_date',
        'duration_days',
        'status',
        'total_cost',
        'penalty_cost',
        'notes',
        'previous_status',
        'is_paid',
        'payment_date',
        'payment_method',
        'payment_reference',
        'final_settlement'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'payment_date' => 'datetime',
        'is_paid' => 'boolean',
        'total_cost' => 'decimal:2',
        'penalty_cost' => 'decimal:2',
        'final_settlement' => 'decimal:2',
        'duration_days' => 'integer',
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
     * Check if rental is overdue (past end_date)
     */
    public function isOverdue(): bool
    {
        $today = Carbon::today();
        $endDate = Carbon::parse($this->end_date);

        return $today->greaterThan($endDate);
    }

    /**
     * Calculate days overdue (after end_date)
     */
    public function daysOverdue(): int
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        $today = Carbon::today();
        $endDate = Carbon::parse($this->end_date);

        return $endDate->diffInDays($today);
    }

    /**
     * Calculate penalty based on days after end_date
     */
    public function calculatePenalty(): float
    {
        $statusToEvaluate = $this->status;

        if ($this->status === self::STATUS_PENDING && $this->previous_status === self::STATUS_OVERDUE) {
            $statusToEvaluate = self::STATUS_OVERDUE;
        }

        if ($statusToEvaluate !== self::STATUS_ACTIVE && $statusToEvaluate !== self::STATUS_OVERDUE) {
            return 0;
        }

        $today = Carbon::today();
        $endDate = Carbon::parse($this->end_date);

        // Hitung hari terlambat setelah end_date
        if ($today->greaterThan($endDate)) {
            $daysLate = $endDate->diffInDays($today);
            
            
            $penaltyPerDay = $this->unit->penalty ?? 5000; // Fallback ke 5000 jika null
            
            return $daysLate * $penaltyPerDay;
        }

        return 0;
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

    /**
     * Get statuses that are considered completed
     */
    public static function getCompletedStatuses(): array
    {
        return [self::STATUS_COMPLETED];
    }

    /**
     * Check if rental is completed
     */
    public function isCompleted(): bool
    {
        return in_array($this->status, self::getCompletedStatuses(), true);
    }

    /**
     * Apply overdue penalty and update status
     */
    public function applyOverduePenalty(): void
    {
        if ($this->status !== self::STATUS_ACTIVE) {
            return;
        }

        $penalty = $this->calculatePenalty();
        if ($penalty > 0) {
            $this->update([
                'status' => self::STATUS_OVERDUE,
                'penalty_cost' => $penalty,
            ]);
        }
    }

    /**
     * Check and auto-update status to overdue if necessary
     */
    public function checkAndUpdateOverdueStatus(): bool
    {
        if ($this->status === self::STATUS_ACTIVE && $this->isOverdue()) {
            $this->applyOverduePenalty();
            return true;
        }
        return false;
    }

    /**
     * Calculate total amount including penalty
     */
    public function getTotalAmountWithPenalty(): float
    {
        return $this->total_cost + ($this->penalty_cost ?? 0);
    }
}
