<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('histories', function (Blueprint $table) {
            if (!Schema::hasColumn('histories', 'era')) {
                $table->string('era', 120)->nullable()->after('cover_path');
            }

            if (!Schema::hasColumn('histories', 'leading_figure')) {
                $table->string('leading_figure', 160)->nullable()->after('era');
            }

            $table->index('era');
            $table->index('leading_figure');
        });
    }

    public function down(): void
    {
        Schema::table('histories', function (Blueprint $table) {
            if (Schema::hasColumn('histories', 'leading_figure')) {
                $table->dropColumn('leading_figure');
            }

            if (Schema::hasColumn('histories', 'era')) {
                $table->dropColumn('era');
            }
        });
    }
};
