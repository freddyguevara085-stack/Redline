<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('game_id')->nullable()->constrained('games')->cascadeOnDelete(); 
            $table->unsignedInteger('points')->default(0);
            $table->integer('score')->default(0);
            $table->unsignedInteger('quizzes_taken')->default(0);
            $table->unsignedInteger('games_played')->default(0);
            $table->string('source')->nullable(); // Ej: trivia, quiz, memoria
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_scores');
    }
};