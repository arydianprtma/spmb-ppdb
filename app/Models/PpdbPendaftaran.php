<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PpdbPendaftaran extends Model
{
    protected $table = 'ppdb_pendaftaran';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->verification_token)) {
                $model->verification_token = Str::random(32);
            }
            if (empty($model->tahun_ajaran)) {
                $activeSetting = PpdbSetting::where('is_active', true)->first();
                $model->tahun_ajaran = $activeSetting ? $activeSetting->tahun_ajaran : date('Y') . '/' . (date('Y') + 1);
            }
        });
    }

    protected $casts = [
        'jadwal_tes_jenis' => 'array',
        'jadwal_tes_tanggal' => 'date',
    ];

    public function siswa()
    {
        return $this->hasOne(PpdbSiswa::class, 'pendaftaran_id');
    }

    public function orangTua()
    {
        return $this->hasMany(PpdbOrangTua::class, 'pendaftaran_id');
    }

    public function ayah()
    {
        return $this->hasOne(PpdbOrangTua::class, 'pendaftaran_id')->where('jenis', 'ayah');
    }

    public function ibu()
    {
        return $this->hasOne(PpdbOrangTua::class, 'pendaftaran_id')->where('jenis', 'ibu');
    }

    public function wali()
    {
        return $this->hasOne(PpdbWali::class, 'pendaftaran_id');
    }

    public function periodik()
    {
        return $this->hasOne(PpdbPeriodik::class, 'pendaftaran_id');
    }

    public function prestasi()
    {
        return $this->hasMany(PpdbPrestasi::class, 'pendaftaran_id');
    }

    public function beasiswa()
    {
        return $this->hasMany(PpdbBeasiswa::class, 'pendaftaran_id');
    }

    public function berkas()
    {
        return $this->hasOne(PpdbBerkas::class, 'pendaftaran_id');
    }
}
