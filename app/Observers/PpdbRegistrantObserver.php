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
            // Cek role yang benar-benar ada di database untuk mencegah RoleDoesNotExist exception dari Spatie
            $existingRoles = \Illuminate\Support\Facades\DB::table('roles')
                ->whereIn('name', ['admin', 'super_admin'])
                ->where('guard_name', 'web')
                ->pluck('name')
                ->toArray();

            if (!empty($existingRoles)) {
                $admins = User::role($existingRoles)->get();
            } else {
                $admins = collect();
            }
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
            // Jika masih kosong, coba ambil semua user yang memiliki is_active = true dan role super_admin/admin di database (sebagai perlindungan terakhir)
            $admins = User::where('is_active', true)->where(function($q) {
                $q->where('role', 'super_admin')
                  ->orWhere('role', 'admin')
                  ->orWhereHas('roles', function($rq) {
                      $rq->whereIn('name', ['admin', 'super_admin']);
                  });
            })->get();
            \Illuminate\Support\Facades\Log::info("PpdbRegistrantObserver: Found ultra-fallback admins: " . $admins->pluck('id')->join(','));
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
