<?php


use App\Http\Controllers\PpdbController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\PrestasiController;
use App\Http\Controllers\ContactController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\VerificationController;

// Public Routes
Route::get('/', function () {
    return redirect('/login');
})->name('home');

// Verification Route (Hanya Admin/Super Admin yang bisa akses)
Route::get('/verifikasi/{no_reg}', [VerificationController::class, 'verify'])
    ->name('verifikasi.publik');

// Dummy routes for MainLayout navbar links to avoid Ziggy errors
Route::get('/tentang-pondok', function() { return redirect(env('APP_URL', 'http://localhost:8080').'/tentang-pondok'); })->name('tentang-pondok');
Route::get('/sejarah', function() { return redirect(env('APP_URL', 'http://localhost:8080').'/sejarah'); })->name('sejarah');
Route::get('/fasilitas', function() { return redirect(env('APP_URL', 'http://localhost:8080').'/fasilitas'); })->name('fasilitas');
Route::get('/visi-misi', function() { return redirect(env('APP_URL', 'http://localhost:8080').'/visi-misi'); })->name('visi-misi');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    // Lupa Password & OTP Routes
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password/send-otp', [AuthController::class, 'sendOtp'])->name('password.send-otp');
    Route::post('/forgot-password/verify-otp', [AuthController::class, 'verifyOtp'])->name('password.verify-otp');
    Route::post('/forgot-password/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset-submit');
});

// Refresh CSRF Token
Route::get('/refresh-csrf', function() {
    return response()->json(['token' => csrf_token()]);
})->name('refresh-csrf');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Berita Routes
Route::get('/berita', [BeritaController::class, 'index'])->name('berita');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.show');

// Prestasi Routes
Route::get('/prestasi', [PrestasiController::class, 'index'])->name('prestasi');

// Sekolah Routes (Placeholders)
Route::get('/sma', function () { return Inertia::render('ComingSoon'); })->name('sekolah.sma');
Route::get('/smp', function () { return Inertia::render('ComingSoon'); })->name('sekolah.smp');

// Contact Routes
Route::get('/kontak', [ContactController::class, 'index'])->name('kontak');
Route::post('/kontak', [ContactController::class, 'store'])->name('kontak.store');

// Protected Routes (Auth required)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // PPDB Routes
    Route::get('/ppdb', [PpdbController::class, 'create'])->name('ppdb.register');
    Route::post('/ppdb', [PpdbController::class, 'store'])->name('ppdb.store');
    Route::get('/ppdb/success/{no_reg}', [PpdbController::class, 'success'])->name('ppdb.success');
    // Profile Routes
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::match(['post', 'patch'], '/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
});
