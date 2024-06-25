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
        Schema::create('reels', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->string("description");
            $table->string("reelsUrl");
            $table->integer("views")->default(0);
            $table->json("like")->default("[]")->nullable();
            $table->json("comments")->default("[]")->nullable();
            $table->unsignedBigInteger("userId");
            $table->foreign("userId")->references("id")->on("users")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reels');
    }
};
