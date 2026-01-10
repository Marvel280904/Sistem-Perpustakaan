<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'member_name',      
        'borrower_phone',    
        'borrower_email',     
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
    // RELATIONSHIPS
    // ======================

    /**
     * Relationship with book
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

}