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
        Schema::create('acecomments', function (Blueprint $table) {
            $table->id();  // Unique identifier for each comment
            $table->unsignedBigInteger('post_id');  // Foreign key to posts table
            $table->unsignedBigInteger('user_id');  // Foreign key to users table
            $table->text('content');  // The actual comment content
            $table->timestamps();  // Created at, Updated at

            // Foreign key constraints
            $table->foreign('post_id')->references('id')->on('aceposts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acecomments');
    }
};
