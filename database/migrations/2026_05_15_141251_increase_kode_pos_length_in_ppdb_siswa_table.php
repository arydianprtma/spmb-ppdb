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
        if (!Schema::hasTable('ppdb_siswa')) return;
        Schema::table('ppdb_siswa', function (Blueprint $table) {
            $table->string('kode_pos', 10)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb_siswa', function (Blueprint $table) {
            $table->string('kode_pos', 5)->nullable()->change();
        });
    }
};
