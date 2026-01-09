<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'author',
        'isbn',
        'stock',
    ];

    // ======================
    // CUSTOM METHODS & SCOPES
    // ======================

    /**
     * Check if book is available for loan
     */
    public function isAvailable(): bool
    {
        return $this->stock > 0;
    }

    /**
     * Decrease stock when book is borrowed
     */
    public function decreaseStock(): bool
    {
        if ($this->stock > 0) {
            $this->decrement('stock');
            return true;
        }
        return false;
    }

    /**
     * Increase stock when book is returned
     */
    public function increaseStock(): bool
    {
        $this->increment('stock');
        return true;
    }

    /**
     * Scope for available books
     */
    public function scopeAvailable($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Scope for unavailable books
     */
    public function scopeUnavailable($query)
    {
        return $query->where('stock', '<=', 0);
    }

    /**
     * Search books by title or author
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('author', 'like', "%{$search}%")
              ->orWhere('isbn', 'like', "%{$search}%");
        });
    }

    /**
     * Relationship with loans
     */
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Get active loans for this book
     */
    public function activeLoans()
    {
        return $this->loans()->where('status', 'active');
    }
}