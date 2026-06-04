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
        if (Schema::hasTable('ppdb_siswa') && !Schema::hasColumn('ppdb_siswa', 'asal_sekolah')) {
            Schema::table('ppdb_siswa', function (Blueprint $table) {
                $table->string('asal_sekolah')->nullable()->after('nama_lengkap');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('ppdb_siswa') && Schema::hasColumn('ppdb_siswa', 'asal_sekolah')) {
            Schema::table('ppdb_siswa', function (Blueprint $table) {
                $table->dropColumn('asal_sekolah');
            });
        }
    }
};
