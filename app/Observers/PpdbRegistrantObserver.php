<?php

namespace App\Observers;

use App\Models\PpdbSiswa;
use App\Models\User;
use Filament\Notifications\Notification;

class PpdbRegistrantObserver
{
    /**
     * Handle the PpdbSiswa "created" event.
     */
    public function created(PpdbSiswa $siswa): void
    {
        \Illuminate\Support\Facades\Log::info("PpdbRegistrantObserver: created hook triggered for Siswa ID {$siswa->id}");

        try {
            $admins = User::role(['admin', 'super_admin'])->get();
            \Illuminate\Support\Facades\Log::info("PpdbRegistrantObserver: Found Spatie role admins: " . $admins->pluck('id')->join(','));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("PpdbRegistrantObserver: Spatie role query failed: " . $e->getMessage());
            $admins = collect();
        }

        if ($admins->isEmpty()) {
            $admins = User::whereIn('role', ['admin', 'super_admin'])->get();
            \Illuminate\Support\Facades\Log::info("PpdbRegistrantObserver: Found fallback column role admins: " . $admins->pluck('id')->join(','));
        }

        if ($admins->isEmpty()) {
            \Illuminate\Support\Facades\Log::warning("PpdbRegistrantObserver: No admins found, notification not sent.");
            return;
        }

        try {
            Notification::make()
                ->title('Pendaftaran PPDB Baru')
                ->body("Siswa baru: {$siswa->nama_lengkap} telah mendaftar.")
                ->icon('heroicon-o-user-plus')
                ->iconColor('success')
                ->sendToDatabase($admins);
            \Illuminate\Support\Facades\Log::info("PpdbRegistrantObserver: Database notification sent successfully.");
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("PpdbRegistrantObserver: Failed to send database notification: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
        }
    }
}
