<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class PortalPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('portal')
            ->path('portal')
            ->login()
            ->passwordReset()
            ->emailVerification()
            ->profile()
            ->spa()
            ->databaseNotifications()
            ->colors([
                'primary' => Color::Emerald,
                'gray' => Color::Slate,
            ])
            ->font('Poppins')
            ->brandName('Portal Pesantren')
            ->brandLogo(asset('Logo Riyad.png'))
            ->brandLogoHeight('3.5rem')
            ->favicon(asset('logo.png'))
            ->sidebarCollapsibleOnDesktop()
            ->databaseNotifications()
            ->databaseNotificationsPolling('10s')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->profile()
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // AccountWidget removed for better design
            ])
            ->middleware([
                \App\Http\Middleware\TrustProxies::class,
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->renderHook(
                'panels::body.start',
                fn (): string => config('app.debug') && config('app.env') !== 'local'
                    ? \Illuminate\Support\Facades\Blade::render('<div class="bg-danger-600 text-white text-center py-2 px-4 font-bold">WARNING: APP_DEBUG is enabled in a non-local environment! Disable it in .env for security.</div>')
                    : ''
            )
            ->renderHook(
                'panels::body.end',
                fn (): string => \Illuminate\Support\Facades\Blade::render("
                    <script>
                        let isFormDirty = false;
                        document.addEventListener('input', () => { isFormDirty = true; });
                        document.addEventListener('change', () => { isFormDirty = true; });
                        document.addEventListener('click', (e) => {
                            if (e.target.closest('button[type=\"submit\"]')) { isFormDirty = false; }
                        });

                        document.addEventListener('livewire:navigate', (event) => {
                            if (isFormDirty) {
                                event.preventDefault();
                                window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'confirm-leave-modal' } }));
                                window.pendingNavigationUrl = event.detail.url;
                            }
                        });
                    </script>

                    <x-filament::modal id=\"confirm-leave-modal\" icon=\"heroicon-o-exclamation-triangle\" icon-color=\"warning\" alignment=\"center\">
                        <x-slot name=\"heading\">Perubahan Belum Disimpan</x-slot>
                        <x-slot name=\"description\">Apakah Anda yakin ingin meninggalkan halaman ini? Semua perubahan yang belum disimpan akan hilang.</x-slot>
                        <div class=\"flex justify-center gap-3\">
                            <x-filament::button color=\"gray\" x-on:click=\"close()\">Batal</x-filament::button>
                            <x-filament::button color=\"warning\" x-on:click=\"window.location.href = window.pendingNavigationUrl\">Tinggalkan</x-filament::button>
                        </div>
                    </x-filament::modal>
                ")
            )
            ->authMiddleware([
                Authenticate::class,
            ])
            ->renderHook(
                'panels::head.end',
                fn() => view('filament.custom-styles')
            )
            ->renderHook(
                'panels::head.end',
                fn() => view('filament.custom-sidebar-styles')
            )
            ->renderHook(
                'panels::scripts.end',
                fn() => view('filament.custom-sidebar-scripts')
            );
    }
}
