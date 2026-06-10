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
                            const renderTurnstile = () => {
                                if (window.turnstile && document.getElementById("turnstile-container")) {
                                    window.turnstile.render("#turnstile-container", {
                                        sitekey: "' . (config('services.cloudflare.turnstile_site_key') ?? env('VITE_TURNSTILE_SITE_KEY', '1x00000000000000000000AA')) . '",
                                        callback: function (token) {
                                            @this.set(\'turnstile_token\', token);
                                            document.getElementById("turnstile-error").style.display = "none";
                                        },
                                        "expired-callback": function () {
                                            @this.set(\'turnstile_token\', null);
                                        },
                                        "error-callback": function () {
                                            @this.set(\'turnstile_token\', null);
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
        if (!$this->turnstile_token) {
            throw ValidationException::withMessages([
                'email' => 'Silakan selesaikan verifikasi keamanan Turnstile.',
            ]);
        }

        // Verify with Cloudflare API
        $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret' => config('services.cloudflare.turnstile_secret_key') ?? env('TURNSTILE_SECRET_KEY'),
            'response' => $this->turnstile_token,
            'remoteip' => request()->ip(),
        ]);

        if (!$response->successful() || !$response->json('success')) {
            $this->turnstile_token = null;
            $this->js('if (window.turnstile) { window.turnstile.reset(); }');

            throw ValidationException::withMessages([
                'email' => 'Verifikasi keamanan Turnstile gagal. Silakan coba lagi.',
            ]);
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
