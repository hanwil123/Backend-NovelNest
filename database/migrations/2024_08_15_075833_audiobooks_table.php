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
        Schema::create('audio_books', function (Blueprint $table) {
            $table->id();  // Auto-incrementing primary key
            $table->foreignId('book_id')->constrained()->onDelete('cascade');  // Foreign key to books table
            $table->string('audio_file');  // Path to the audio file
            $table->string('narrator')->nullable();  // Name of the narrator
            $table->integer('duration');  // Duration of the audio book in minutes
            $table->timestamps();  // Adds created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audio_books');

    }
};
