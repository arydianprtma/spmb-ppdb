<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ppdb_berkas')) return;
        Schema::create('ppdb_berkas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('ppdb_pendaftaran')->onDelete('cascade');

            // Wajib
            $table->string('ijazah_skhu')->nullable()->comment('Ijazah/SKHU dilegalisir');
            $table->string('akte_kelahiran')->nullable()->comment('Akte Kelahiran');
            $table->string('ktp_orang_tua')->nullable()->comment('KTP Ayah atau Ibu');
            $table->string('kartu_keluarga')->nullable()->comment('Kartu Keluarga (KK)');
            $table->string('surat_sehat')->nullable()->comment('Surat Keterangan Sehat');
            $table->string('surat_kelakuan_baik')->nullable()->comment('Surat Keterangan Kelakuan Baik');

            // Opsional (kartu sosial)
            $table->string('kartu_kks_pkh')->nullable()->comment('Kartu KKS/PKH');
            $table->string('kartu_kps')->nullable()->comment('Kartu KPS');
            $table->string('kartu_kip')->nullable()->comment('Kartu Indonesia Pintar (KIP)');
            $table->string('kartu_kis_bpjs')->nullable()->comment('Kartu KIS/BPJS Kesehatan');
            $table->string('kartu_nisn')->nullable()->comment('Bukti NISN');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_berkas');
    }
};
