<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Expand status ENUM to include diterima & ditolak
        DB::statement("ALTER TABLE ppdb_pendaftaran MODIFY COLUMN status ENUM('pending','verified','diterima','ditolak','rejected') DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE ppdb_pendaftaran MODIFY COLUMN status ENUM('pending','verified','rejected') DEFAULT 'pending'");
    }
};
