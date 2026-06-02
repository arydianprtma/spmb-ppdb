<?php

namespace App\Http\Controllers;

use App\Models\PpdbPendaftaran;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class VerificationController extends Controller
{
    /**
     * Tampilkan halaman verifikasi (Hanya untuk Admin/Super Admin)
     */
    public function verify(Request $request, string $no_reg)
    {
        // Cek apakah user sudah login dan memiliki role admin atau super_admin
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user || !$user->hasAnyRole(['admin', 'super_admin'])) {
            abort(404);
        }

        $token = $request->query('token');
        
        $pendaftaran = PpdbPendaftaran::with(['siswa'])
            ->where('no_reg', $no_reg)
            ->where('verification_token', $token)
            ->first();

        return view('verifikasi.show', [
            'pendaftaran' => $pendaftaran,
            'isValid' => !is_null($pendaftaran),
            'no_reg' => $no_reg
        ]);
    }

    /**
     * Mendapatkan URL QR Code untuk pendaftaran tertentu
     */
    public static function getQrCodeUrl(PpdbPendaftaran $pendaftaran)
    {
        if (empty($pendaftaran->verification_token)) {
            $pendaftaran->verification_token = \Illuminate\Support\Str::random(32);
            $pendaftaran->save();
        }

        $directory = 'public/qr';
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }

        $filename = "qr_{$pendaftaran->no_reg}.svg";
        $path = "{$directory}/{$filename}";
        
        $portalUrl = rtrim(config('app.portal_url'), '/');
        $url = "{$portalUrl}/verifikasi/{$pendaftaran->no_reg}?token={$pendaftaran->verification_token}";

        // Generate ulang jika file tidak ada ATAU jika URL di dalam QR lama mengandung 'localhost'
        // Namun karena kita ingin memastikan QR selalu up-to-date dengan IP, kita generate ulang saja.
        $qrCode = QrCode::format('svg')
            ->size(300)
            ->margin(1)
            ->errorCorrection('H')
            ->generate($url);

        Storage::put($path, $qrCode);

        return Storage::url($path);
    }
}
