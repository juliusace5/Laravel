<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Post owner
            $table->foreignId('actor_id')->constrained('users')->onDelete('cascade'); // Commenter
            $table->foreignId('post_id')->constrained()->onDelete('cascade'); // Associated post
            $table->string('type');
            $table->string('message');
            $table->boolean('read')->default(false); // Track if notification is read
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}

