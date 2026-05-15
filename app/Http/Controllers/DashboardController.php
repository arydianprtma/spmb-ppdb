<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SpmbPendaftaran;
use App\Models\SpmbSetting;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $pendaftaran = SpmbPendaftaran::with(['siswa', 'berkas'])
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        // Statistik global untuk dashboard
        $stats = [
            'total_smp' => SpmbPendaftaran::where('tingkat', 'smp')->count(),
            'total_sma' => SpmbPendaftaran::where('tingkat', 'sma')->count(),
        ];

        $setting = SpmbSetting::where('is_active', true)->latest()->first();

        return Inertia::render('Spmb/Dashboard', [
            'pendaftaran' => $pendaftaran,
            'stats' => $stats,
            'spmbSetting' => [
                'isOpen' => SpmbSetting::isOpen(),
                'tahunAjaran' => $setting?->tahun_ajaran,
                'tglTutup' => $setting?->tgl_tutup?->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}
