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
        Schema::create('aceposts', function (Blueprint $table) {
            $table->id();  // Unique identifier for each post
            $table->unsignedBigInteger('user_id');  // Foreign key to users table
            $table->text('content');  // The actual post content
            $table->timestamps();  // Created at, Updated at

            // Define the foreign key constraint on user_id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aceposts');
    }
};
