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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("chatId");
            $table->foreign("chatId")->references("id")->on("chats");
            $table->unsignedBigInteger("senderId");
            $table->foreign("senderId")->references("id")->on("users");
            $table->unsignedBigInteger("receiverId");
            $table->foreign("receiverId")->references("id")->on("users");
            $table->text("body");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
