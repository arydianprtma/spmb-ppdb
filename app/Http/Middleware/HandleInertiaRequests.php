<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => function () use ($request): ?array {
                    $user = $request->user();

                    if (!$user) {
                        return null;
                    }

                    $avatarPath = $user->avatar;
                    $avatarUrl = null;

                    if ($avatarPath) {
                        if (filter_var($avatarPath, FILTER_VALIDATE_URL)) {
                            $avatarUrl = $avatarPath;
                        } else {
                            $avatarUrl = '/storage/' . ltrim($avatarPath, '/');
                        }
                    }

                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'avatar' => $avatarPath,
                        'avatar_url' => $avatarUrl,
                    ];
                },
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'status' => fn () => $request->session()->get('status'),
                'email' => fn () => $request->session()->get('email'),
                'whatsapp_masked' => fn () => $request->session()->get('whatsapp_masked'),
                'otp_verified' => fn () => $request->session()->get('otp_verified'),
                'otp_code' => fn () => $request->session()->get('otp_code'),
            ],
        ];
    }
}
