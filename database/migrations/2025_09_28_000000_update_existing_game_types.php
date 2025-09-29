<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::table('games')
            ->whereIn('type', ['trivia', 'ruleta', 'juicio'])
            ->update(['type' => 'quiz']);
    }

    public function down(): void
    {
        DB::table('games')
            ->where('type', 'quiz')
            ->update(['type' => 'trivia']);
    }
};
