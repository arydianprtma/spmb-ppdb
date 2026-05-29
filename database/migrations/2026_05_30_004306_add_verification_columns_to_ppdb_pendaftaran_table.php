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
        Schema::table('ppdb_pendaftaran', function (Blueprint $table) {
            if (!Schema::hasColumn('ppdb_pendaftaran', 'verification_token')) {
                $table->string('verification_token')->nullable()->unique()->after('no_reg');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb_pendaftaran', function (Blueprint $table) {
            $table->dropColumn('verification_token');
        });
    }
};
