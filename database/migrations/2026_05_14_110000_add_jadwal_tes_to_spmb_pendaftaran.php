<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('ppdb_pendaftaran', 'jadwal_tes_tanggal')) {
            Schema::table('ppdb_pendaftaran', function (Blueprint $table) {
                $table->date('jadwal_tes_tanggal')->nullable()->after('catatan_admin');
                $table->time('jadwal_tes_jam')->nullable()->after('jadwal_tes_tanggal');
                $table->json('jadwal_tes_jenis')->nullable()->after('jadwal_tes_jam');
                $table->string('jadwal_tes_lokasi')->nullable()->after('jadwal_tes_jenis');
            });
        }
    }

    public function down(): void
    {
        Schema::table('ppdb_pendaftaran', function (Blueprint $table) {
            $table->dropColumn(['jadwal_tes_tanggal', 'jadwal_tes_jam', 'jadwal_tes_jenis', 'jadwal_tes_lokasi']);
        });
    }
};
