<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('ppdb_pendaftaran')) return;
        
        if (!Schema::hasColumn('ppdb_pendaftaran', 'catatan_admin')) {
            Schema::table('ppdb_pendaftaran', function (Blueprint $table) {
                $table->text('catatan_admin')->nullable()->after('status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('ppdb_pendaftaran') && Schema::hasColumn('ppdb_pendaftaran', 'catatan_admin')) {
            Schema::table('ppdb_pendaftaran', function (Blueprint $table) {
                $table->dropColumn('catatan_admin');
            });
        }
    }
};
