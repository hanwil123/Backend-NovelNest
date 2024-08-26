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
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->id();  // Auto-incrementing primary key
            $table->foreignId('user_id')
                  ->constrained('users-google')  // Tabel yang menjadi acuan
                  ->onDelete('cascade');  // Foreign key to users-google table
            $table->foreignId('book_id')->constrained()->onDelete('cascade');  // Foreign key to books table
            $table->integer('page_number');  // Page number where the bookmark is placed
            $table->timestamps();  // Adds created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookmarks');
    }
};
