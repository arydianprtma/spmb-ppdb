<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SpmbPendaftaran;
use App\Models\SpmbSetting;
use App\Http\Controllers\VerificationController;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $pendaftaran = SpmbPendaftaran::with(['siswa', 'berkas', 'orangTua', 'wali'])
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        $qrCodeUrl = null;
        if ($pendaftaran) {
            $qrCodeUrl = VerificationController::getQrCodeUrl($pendaftaran);
        }

        // Statistik global untuk dashboard
        $stats = [
            'total_smp' => SpmbPendaftaran::where('tingkat', 'smp')->count(),
            'total_sma' => SpmbPendaftaran::where('tingkat', 'sma')->count(),
        ];

        $setting = SpmbSetting::where('is_active', true)->latest()->first();

        return Inertia::render('Spmb/Dashboard', [
            'pendaftaran' => $pendaftaran,
            'qrCodeUrl' => $qrCodeUrl,
            'stats' => $stats,
            'spmbSetting' => [
                'isOpen' => SpmbSetting::isOpen(),
                'tahunAjaran' => $setting?->tahun_ajaran,
                'tglTutup' => $setting?->tgl_tutup?->format('Y-m-d H:i:s'),
                'kartuHeader1' => $setting?->kartu_header_1,
                'kartuHeader2' => $setting?->kartu_header_2,
                'kartuAlamat' => $setting?->kartu_alamat,
                'kartuLogo' => $setting?->kartu_logo ? \Illuminate\Support\Facades\Storage::url($setting->kartu_logo) : null,
            ],
        ]);
    }
}
