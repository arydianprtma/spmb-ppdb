<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Temporarily change to VARCHAR to avoid ENUM constraint during data update
        DB::statement("ALTER TABLE ppdb_pendaftaran MODIFY COLUMN status VARCHAR(30) DEFAULT 'pending'");

        // Step 2: Map old status values to new ones
        DB::statement("UPDATE ppdb_pendaftaran SET status = 'jadwal_tes' WHERE status = 'verified'");
        DB::statement("UPDATE ppdb_pendaftaran SET status = 'ditolak' WHERE status = 'rejected'");

        // Step 3: Apply the final ENUM definition
        DB::statement("ALTER TABLE ppdb_pendaftaran MODIFY COLUMN status ENUM(
            'pending',
            'jadwal_tes',
            'tes_berlangsung',
            'wawancara',
            'diterima_ula',
            'diterima_wustho',
            'diterima_ulya',
            'diterima',
            'ditolak'
        ) DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE ppdb_pendaftaran MODIFY COLUMN status ENUM('pending','verified','diterima','ditolak','rejected') DEFAULT 'pending'");
    }
};
