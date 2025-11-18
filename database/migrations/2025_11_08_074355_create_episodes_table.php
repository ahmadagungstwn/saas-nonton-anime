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
        Schema::create('episodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anime_id')->constrained('animes')->cascadeOnDelete();
            $table->integer('episode_number');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('thumbnail');
            $table->string('rating')->nullable();
            $table->text('synopsis');
            $table->text('video');
            $table->string('duration');
            $table->boolean('is_locked')->default(false);
            $table->integer('unlock_cost')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('episodes');
    }
};
