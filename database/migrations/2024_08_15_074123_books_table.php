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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('penulis');
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Foreign key to categories
            $table->string('isibuku'); // Store the file path as a string
            $table->string('cover_image')->nullable();
            $table->text('sinopsis');
            $table->integer('halaman');
            $table->float('rating');
            $table->time('audio_length')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
