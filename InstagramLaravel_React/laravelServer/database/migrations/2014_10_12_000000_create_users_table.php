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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean("isAdmin")->default(false);
            $table->string("biotxt")->nullable()->default("");
            $table->string("profilePhoto")->nullable()->default(null);
            $table->json("stories")->default("[]")->nullable();
            $table->json("posts")->default("[]")->nullable();
            $table->json("reels")->default("[]")->nullable();
            $table->json("savedPosts")->default("[]")->nullable();
            $table->json("follower")->default("[]")->nullable();
            $table->json("following")->default("[]")->nullable();
            $table->json("notifications")->default("[]")->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
