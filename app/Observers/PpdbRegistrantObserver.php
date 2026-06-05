<?php

namespace App\Observers;

use App\Models\PpdbPendaftaran;
use App\Models\User;
use Filament\Notifications\Notification;

class PpdbRegistrantObserver
{
    /**
     * Handle the PpdbPendaftaran "created" event.
     */
    public function created(PpdbPendaftaran $pendaftaran): void
    {
        try {
            $admins = User::role(['admin', 'super_admin'])->get();
        } catch (\Throwable $e) {
            $admins = collect();
        }

        if ($admins->isEmpty()) {
            $admins = User::whereIn('role', ['admin', 'super_admin'])->get();
        }

        if ($admins->isEmpty()) {
            return;
        }

        Notification::make()
            ->title('Pendaftaran PPDB Baru')
            ->body("Siswa baru: {$pendaftaran->siswa?->nama_lengkap} telah mendaftar.")
            ->icon('heroicon-o-user-plus')
            ->iconColor('success')
            ->sendToDatabase($admins);
    }
}
