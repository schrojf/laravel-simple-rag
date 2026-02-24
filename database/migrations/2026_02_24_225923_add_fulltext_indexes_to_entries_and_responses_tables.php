<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! in_array(DB::getDriverName(), ['mysql', 'mariadb', 'pgsql'])) {
            return;
        }

        Schema::table('entries', function (Blueprint $table) {
            $table->fullText(['title', 'content'])->language('english');
        });

        Schema::table('responses', function (Blueprint $table) {
            $table->fullText(['content'])->language('english');
        });
    }

    public function down(): void
    {
        if (! in_array(DB::getDriverName(), ['mysql', 'mariadb', 'pgsql'])) {
            return;
        }

        Schema::table('entries', function (Blueprint $table) {
            $table->dropFullText(['title', 'content']);
        });

        Schema::table('responses', function (Blueprint $table) {
            $table->dropFullText(['content']);
        });
    }
};
