<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Loan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'book_id',
        'user_id',
        'loan_date',
        'due_date',
        'return_date',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'loan_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
    ];

    // ======================
    // CONSTANTS FOR STATUS
    // ======================

    const STATUS_ACTIVE = 'active';
    const STATUS_RETURNED = 'returned';
    const STATUS_OVERDUE = 'overdue';

    /**
     * Get all status options
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_ACTIVE => 'Aktif',
            self::STATUS_RETURNED => 'Dikembalikan',
            self::STATUS_OVERDUE => 'Terlambat',
        ];
    }

    // ======================
    // CUSTOM METHODS & SCOPES
    // ======================

    /**
     * Check if loan is active
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if loan is returned
     */
    public function isReturned(): bool
    {
        return $this->status === self::STATUS_RETURNED;
    }

    /**
     * Check if loan is overdue
     */
    public function isOverdue(): bool
    {
        return $this->status === self::STATUS_OVERDUE;
    }

    /**
     * Check if loan is actually overdue based on due date
     */
    public function checkIfOverdue(): bool
    {
        if ($this->isActive() && $this->due_date < now()) {
            $this->update(['status' => self::STATUS_OVERDUE]);
            return true;
        }
        return false;
    }

    /**
     * Mark loan as returned
     */
    public function markAsReturned(): bool
    {
        $this->update([
            'status' => self::STATUS_RETURNED,
            'return_date' => now(),
        ]);

        // Increase book stock
        $this->book->increaseStock();

        return true;
    }

    /**
     * Calculate days overdue
     */
    public function daysOverdue(): int
    {
        if ($this->isOverdue()) {
            return now()->diffInDays($this->due_date);
        }
        return 0;
    }

    /**
     * Calculate fine (denda)
     * Contoh: Rp 1.000 per hari keterlambatan
     */
    public function calculateFine(): int
    {
        if ($this->isOverdue()) {
            $days = $this->daysOverdue();
            return $days * 1000; // Rp 1.000 per hari
        }
        return 0;
    }

    // ======================
    // SCOPES
    // ======================

    /**
     * Scope for active loans
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope for returned loans
     */
    public function scopeReturned($query)
    {
        return $query->where('status', self::STATUS_RETURNED);
    }

    /**
     * Scope for overdue loans
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', self::STATUS_OVERDUE);
    }

    /**
     * Scope for loans due soon (within 2 days)
     */
    public function scopeDueSoon($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
                     ->where('due_date', '<=', now()->addDays(2))
                     ->where('due_date', '>', now());
    }

    // ======================
    // RELATIONSHIPS
    // ======================

    /**
     * Relationship with book
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Relationship with user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}