<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;

class CustomLogin extends BaseLogin
{
    public ?string $turnstile_token = null;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
                Html::make(new HtmlString('
                    <div style="margin: 10px 0; display: flex; justify-content: center;">
                        <div id="turnstile-container"></div>
                    </div>
                    <div id="turnstile-error" style="color: #da3636; font-size: 0.8rem; text-align: center; margin-top: -5px; display: none;">
                        Silakan selesaikan verifikasi keamanan Turnstile.
                    </div>
                    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit" async defer></script>
                    <script>
                        (function() {
                            const setLivewireToken = (val) => {
                                const el = document.getElementById("turnstile-container");
                                const wireEl = el ? el.closest("[wire\\:id]") : null;
                                const wireId = wireEl ? wireEl.getAttribute("wire:id") : null;
                                if (wireEl && wireEl.__livewire) {
                                    wireEl.__livewire.set("turnstile_token", val);
                                } else if (wireId && window.Livewire) {
                                    window.Livewire.find(wireId).set("turnstile_token", val);
                                }
                            };

                            const renderTurnstile = () => {
                                if (window.turnstile && document.getElementById("turnstile-container")) {
                                    window.turnstile.render("#turnstile-container", {
                                        sitekey: "' . (config('services.cloudflare.turnstile_site_key') ?? env('VITE_TURNSTILE_SITE_KEY', '0x4AAAAAADiJFNnVjj_xSKi_')) . '",
                                        action: "filament-login",
                                        callback: function (token) {
                                            setLivewireToken(token);
                                            const err = document.getElementById("turnstile-error");
                                            if (err) err.style.display = "none";
                                        },
                                        "expired-callback": function () {
                                            setLivewireToken(null);
                                        },
                                        "error-callback": function () {
                                            setLivewireToken(null);
                                        }
                                    });
                                }
                            };
                            
                            if (window.turnstile) {
                                renderTurnstile();
                            } else {
                                window.onloadTurnstileCallback = renderTurnstile;
                            }
                            
                            document.addEventListener("livewire:navigated", renderTurnstile);
                            document.addEventListener("livewire:load", renderTurnstile);
                        })();
                    </script>
                ')),
            ]);
    }

    public function authenticate(): ?\Filament\Auth\Http\Responses\Contracts\LoginResponse
    {
        $isLocal = app()->environment('local');

        if (!$isLocal) {
            if (!$this->turnstile_token) {
                throw ValidationException::withMessages([
                    'email' => 'Silakan selesaikan verifikasi keamanan Turnstile.',
                ]);
            }

            // Verify with Cloudflare API
            $response = Http::asForm()->timeout(10)->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => config('services.cloudflare.turnstile_secret_key') ?? env('TURNSTILE_SECRET_KEY'),
                'response' => $this->turnstile_token,
                'remoteip' => request()->ip(),
            ]);

            $data = $response->json();
            $allowedHostnames = [
                'ppdb.riyadussalikin.ponpes.id',
                'riyadussalikin.ponpes.id',
                'www.riyadussalikin.ponpes.id',
                'localhost',
                '127.0.0.1',
            ];

            if (
                !$response->successful() ||
                !($data['success'] ?? false) ||
                (!empty($data['action']) && $data['action'] !== 'filament-login') ||
                (!empty($data['hostname']) && !in_array($data['hostname'], $allowedHostnames, true))
            ) {
                $this->turnstile_token = null;
                $this->js('if (window.turnstile) { window.turnstile.reset(); }');

                throw ValidationException::withMessages([
                    'email' => 'Verifikasi keamanan Turnstile gagal. Silakan coba lagi.',
                ]);
            }
        }

        try {
            return parent::authenticate();
        } catch (\Exception $e) {
            $this->turnstile_token = null;
            $this->js('if (window.turnstile) { window.turnstile.reset(); }');
            throw $e;
        }
    }
}
