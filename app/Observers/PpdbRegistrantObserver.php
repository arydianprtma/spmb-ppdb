<?php

namespace App\Observers;

use App\Models\PpdbRegistrant;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class PpdbRegistrantObserver
{
    /**
     * Handle the PpdbRegistrant "created" event.
     */
    public function created(PpdbRegistrant $registrant): void
    {
        $admins = User::whereIn('role', ['admin', 'super_admin'])->get();

        if ($admins->isEmpty()) {
            return;
        }

        Notification::make()
            ->title('Pendaftaran PPDB Baru')
            ->body("Siswa Baru: {$registrant->nama_lengkap} telah mendaftar.")
            ->icon('heroicon-o-user-plus')
            ->iconColor('success')
            ->actions([
                Action::make('lihat')
                    ->label('Cek Pendaftar')
                    ->url(route('filament.portal.resources.ppdb.index'))
                    ->button(),
            ])
            ->sendToDatabase($admins)
            ->broadcast($admins);
    }
}
