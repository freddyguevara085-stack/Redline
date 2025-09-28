<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('library_items', function (Blueprint $table) {
            $table->string('cover_path')->nullable()->after('description');
            $table->string('video_url')->nullable()->after('file_path');
            $table->text('video_caption')->nullable()->after('video_url');
        });
    }

    public function down(): void
    {
        Schema::table('library_items', function (Blueprint $table) {
            $table->dropColumn(['cover_path', 'video_url', 'video_caption']);
        });
    }
};
