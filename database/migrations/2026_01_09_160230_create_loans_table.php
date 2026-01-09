<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            
            // BUAT KOLOM DULU
            $table->unsignedBigInteger('book_id');
            $table->unsignedBigInteger('user_id');
            
            // BUAT FOREIGN KEY
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // KOLOM LAINNYA
            $table->date('loan_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->string('status', 20)->default('active'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
