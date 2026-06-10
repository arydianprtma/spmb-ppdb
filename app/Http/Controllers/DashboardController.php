<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PpdbPendaftaran;
use App\Models\PpdbSetting;
use App\Http\Controllers\VerificationController;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $pendaftaran = PpdbPendaftaran::with(['siswa', 'berkas', 'orangTua', 'wali', 'kelas'])
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        $qrCodeUrl = null;
        if ($pendaftaran) {
            $qrCodeUrl = VerificationController::getQrCodeUrl($pendaftaran);
        }

        $canTransition = false;
        if ($pendaftaran && $pendaftaran->tingkat === 'smp' && $pendaftaran->siswa && $pendaftaran->siswa->status === 'lulus') {
            $canTransition = true;
        }

        // Statistik global untuk dashboard
        $stats = [
            'total_smp' => PpdbPendaftaran::where('tingkat', 'smp')->count(),
            'total_sma' => PpdbPendaftaran::where('tingkat', 'sma')->count(),
        ];

        $setting = PpdbSetting::where('is_active', true)->latest()->first();

        return Inertia::render('Ppdb/Dashboard', [
            'pendaftaran' => $pendaftaran,
            'qrCodeUrl' => $qrCodeUrl,
            'stats' => $stats,
            'canTransition' => $canTransition,
            'ppdbSetting' => [
                'isOpen' => PpdbSetting::isOpen(),
                'tahunAjaran' => $setting?->tahun_ajaran,
                'tglTutup' => $setting?->tgl_tutup?->format('Y-m-d H:i:s'),
                'pesanTutup' => $setting?->pesan_tutup,
                'kartuHeader1' => $setting?->kartu_header_1,
                'kartuHeader2' => $setting?->kartu_header_2,
                'kartuAlamat' => $setting?->kartu_alamat,
                // Logo disimpan di storage admin (ppdb), bangun URL absolut agar bisa diakses dari portal PPDB
                'kartuLogo' => $setting?->kartu_logo
                    ? rtrim(env('PPDB_ADMIN_URL', config('app.url')), '/') . '/storage/' . $setting->kartu_logo
                    : null,
            ],
        ]);
    }
}
