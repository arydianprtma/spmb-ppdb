<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use App\Services\WhatsappService;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showRegister()
    {
        return Inertia::render('Auth/Register');
    }

    public function register(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'whatsapp' => 'required|string|min:10|max:15|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ];

        $request->validate($rules, [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'whatsapp.min' => 'Nomor WhatsApp minimal 10 digit.',
            'whatsapp.max' => 'Nomor WhatsApp maksimal 15 digit.',
            'whatsapp.unique' => 'Nomor WhatsApp ini sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        if (!$this->verifyTurnstile($request, 'register')) {
            return back()->withErrors([
                'turnstile_token' => 'Verifikasi keamanan Turnstile gagal. Silakan coba lagi.',
                'cf-turnstile-response' => 'Verifikasi keamanan Turnstile gagal. Silakan coba lagi.',
            ])->withInput($request->except('password', 'password_confirmation'));
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'whatsapp' => $request->whatsapp,
            'password' => Hash::make($request->password),
            'role'     => 'siswa',
            'is_active'=> true,
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }

    public function showLogin()
    {
        return Inertia::render('Auth/Login');
    }

    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $request->validate($rules, [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        if (!$this->verifyTurnstile($request, 'login')) {
            return back()->withErrors([
                'turnstile_token' => 'Verifikasi keamanan Turnstile gagal. Silakan coba lagi.',
                'cf-turnstile-response' => 'Verifikasi keamanan Turnstile gagal. Silakan coba lagi.',
            ])->onlyInput('email');
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function showForgotPassword()
    {
        return Inertia::render('Auth/ForgotPassword');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Alamat email tidak terdaftar di sistem.',
        ]);

        if (!$this->verifyTurnstile($request, 'forgot-password')) {
            return back()->withErrors([
                'turnstile_token' => 'Verifikasi keamanan Turnstile gagal. Silakan coba lagi.',
                'cf-turnstile-response' => 'Verifikasi keamanan Turnstile gagal. Silakan coba lagi.',
            ])->onlyInput('email');
        }

        $user = User::where('email', $request->email)->first();

        if (!$user->whatsapp) {
            return back()->withErrors([
                'email' => 'Akun Anda belum memiliki nomor WhatsApp terdaftar. Silakan hubungi admin.',
            ]);
        }

        // Generate 6-digit cryptographically secure OTP
        $otp = (string) random_int(100000, 999999);
        $user->otp_code = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        // Send OTP via WhatsappService
        $whatsappService = new WhatsappService();
        $message = "Kode OTP untuk atur ulang kata sandi Anda di PPDB Pondok Pesantren Riyadussalikin adalah: *{$otp}*.\n\nKode ini berlaku selama 10 menit. Jangan sebarkan kode ini kepada siapa pun.";
        
        $whatsappService->sendMessage($user->whatsapp, $message);

        // Mask WhatsApp number for security display
        $maskedWa = substr($user->whatsapp, 0, 4) . 'xxxxx' . substr($user->whatsapp, -3);

        return back()->with([
            'status' => 'otp_sent',
            'email' => $user->email,
            'whatsapp_masked' => $maskedWa,
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp_code' => 'required|string|size:6',
        ], [
            'email.required' => 'Email wajib diisi.',
            'otp_code.required' => 'Kode OTP wajib diisi.',
            'otp_code.size' => 'Kode OTP harus berupa 6 digit.',
        ]);

        $user = User::where('email', $request->email)
            ->where('otp_code', $request->otp_code)
            ->where('otp_expires_at', '>', Carbon::now())
            ->first();

        if (!$user) {
            return back()->withErrors([
                'otp_code' => 'Kode OTP tidak valid atau sudah kedaluwarsa.',
            ]);
        }

        return back()->with([
            'status' => 'otp_verified',
            'email' => $user->email,
            'otp_code' => $request->otp_code,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp_code' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.required' => 'Email wajib diisi.',
            'otp_code.required' => 'Kode OTP wajib diisi.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::where('email', $request->email)
            ->where('otp_code', $request->otp_code)
            ->where('otp_expires_at', '>', Carbon::now())
            ->first();

        if (!$user) {
            return back()->withErrors([
                'otp_code' => 'Sesi reset password tidak valid atau sudah kedaluwarsa.',
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        return redirect()->route('login')->with('success', 'Kata sandi berhasil diubah. Silakan masuk kembali.');
    }

    /**
     * Verifikasi token Cloudflare Turnstile canonical (gate, don't replace).
     */
    private function verifyTurnstile(Request $request, string $expectedAction): bool
    {
        if (app()->environment('local')) {
            return true;
        }

        $token = $request->input('cf-turnstile-response') ?? $request->input('turnstile_token');
        if (!$token) {
            return false;
        }

        $secret = config('services.cloudflare.turnstile_secret_key') ?? env('TURNSTILE_SECRET_KEY');
        if (!$secret) {
            return true;
        }

        try {
            $response = \Illuminate\Support\Facades\Http::asForm()->timeout(10)->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $secret,
                'response' => $token,
                'remoteip' => $request->ip(),
            ]);

            if (!$response->successful()) {
                return false;
            }

            $data = $response->json();
            if (!($data['success'] ?? false)) {
                return false;
            }

            // Validasi action jika ada
            if (!empty($data['action']) && $data['action'] !== $expectedAction) {
                return false;
            }

            // Validasi hostname terhadap allowlist domain aplikasi
            $allowedHostnames = [
                'ppdb.riyadussalikin.ponpes.id',
                'riyadussalikin.ponpes.id',
                'www.riyadussalikin.ponpes.id',
                'localhost',
                '127.0.0.1',
            ];

            if (!empty($data['hostname']) && !in_array($data['hostname'], $allowedHostnames, true)) {
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Turnstile verification exception: ' . $e->getMessage());
            return false;
        }
    }
}
