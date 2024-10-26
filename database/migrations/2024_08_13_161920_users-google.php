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
        Schema::create('users-google', function (Blueprint $table) {
            $table->id();
            $table->string('google_id')->unique();
            $table->string('google_token');
            $table->string('google_refresh_token')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->enum('gender', ['Male', 'Female'])->nullable();
            $table->string('number_phone')->nullable();
            $table->string('address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
