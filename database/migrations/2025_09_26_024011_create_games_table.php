<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up(): void {
    Schema::create('games', function (Blueprint $t){
        $t->id();
        $t->foreignId('user_id')->constrained()->cascadeOnDelete();
        $t->string('title',160);
        $t->text('description')->nullable();
        $t->unsignedInteger('points_per_question')->default(10);
        $t->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
