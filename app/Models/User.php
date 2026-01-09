<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',        
        'role',         
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ======================
    // CUSTOM METHODS & SCOPES
    // ======================

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is member
     */
    public function isMember(): bool
    {
        return $this->role === 'member';
    }

    /**
     * Scope for admin users
     */
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope for member users
     */
    public function scopeMember($query)
    {
        return $query->where('role', 'member');
    }

    /**
     * Relationship with loans (peminjaman)
     */
    public function loans()
    {
        return $this->hasMany(Loan::class, 'user_id');
    }

    /**
     * Get active loans (yang belum dikembalikan)
     */
    public function activeLoans()
    {
        return $this->loans()->where('status', 'active');
    }

    /**
     * Get returned loans (yang sudah dikembalikan)
     */
    public function returnedLoans()
    {
        return $this->loans()->where('status', 'returned');
    }

    /**
     * Get overdue loans (yang telat)
     */
    public function overdueLoans()
    {
        return $this->loans()->where('status', 'overdue');
    }
}