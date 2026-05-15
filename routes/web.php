<?php


use App\Http\Controllers\SpmbController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\PrestasiController;
use App\Http\Controllers\ContactController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public Routes
Route::get('/', function () {
    return redirect('/login');
})->name('home');

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
});

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
    // SPMB (PPDB) Routes
    Route::get('/spmb', [SpmbController::class, 'create'])->name('spmb.register');
    Route::post('/spmb', [SpmbController::class, 'store'])->name('spmb.store');
    Route::get('/spmb/success/{no_reg}', [SpmbController::class, 'success'])->name('spmb.success');
    // Profile Routes
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::match(['post', 'patch'], '/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
});
