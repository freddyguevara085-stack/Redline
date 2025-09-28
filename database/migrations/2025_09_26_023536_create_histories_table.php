<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up(): void {
    Schema::create('histories', function (Blueprint $t){
        $t->id();
        $t->foreignId('user_id')->constrained()->cascadeOnDelete();
        $t->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
        $t->string('title',160);
        $t->string('slug',180)->unique();
        $t->text('excerpt')->nullable();
        $t->longText('content'); // TRIX/HTML
        $t->string('cover_path')->nullable();
        $t->timestamp('published_at')->nullable();
        $t->timestamps();
        $t->index(['category_id','published_at']);
    });
}
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('histories');
    }
}
