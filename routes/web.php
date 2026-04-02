<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Dokter;
use App\Http\Controllers\Pasien;
use Illuminate\Support\Facades\Route;

// Root redirect
Route::get('/', fn () => redirect()->route('login'));

// ─── Auth ──────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ─── Admin ─────────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Dokter
    Route::resource('dokter', Admin\DokterController::class)->except(['show']);
    Route::patch('dokter/{dokter}/toggle', [Admin\DokterController::class, 'toggle'])->name('dokter.toggle');

    // Pasien
    Route::get('pasien', [Admin\PasienController::class, 'index'])->name('pasien.index');
    Route::get('pasien/{pasien}', [Admin\PasienController::class, 'show'])->name('pasien.show');
    Route::delete('pasien/{pasien}', [Admin\PasienController::class, 'destroy'])->name('pasien.destroy');

    // Appointments
    Route::get('appointments', [Admin\AppointmentController::class, 'index'])->name('appointments.index');
    Route::patch('appointments/{appointment}/status', [Admin\AppointmentController::class, 'updateStatus'])->name('appointments.status');

    // Laporan & PDF
    Route::get('laporan', [Admin\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/export-pdf', [Admin\LaporanController::class, 'exportPdf'])->name('laporan.pdf');
});

// ─── Dokter ────────────────────────────────────────────────────────────────
Route::prefix('dokter')->name('dokter.')->middleware(['auth', 'role:dokter'])->group(function () {
    Route::get('/dashboard', [Dokter\DashboardController::class, 'index'])->name('dashboard');

    // Appointments
    Route::get('appointments', [Dokter\AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('appointments/{appointment}', [Dokter\AppointmentController::class, 'show'])->name('appointments.show');
    Route::patch('appointments/{appointment}/status', [Dokter\AppointmentController::class, 'updateStatus'])->name('appointments.status');

    // Rekam Medis
    Route::post('rekam-medis', [Dokter\RekamMedisController::class, 'store'])->name('rekam-medis.store');
    Route::patch('rekam-medis/{rekamMedis}', [Dokter\RekamMedisController::class, 'update'])->name('rekam-medis.update');
    Route::get('rekam-medis/{rekamMedis}', [Dokter\RekamMedisController::class, 'show'])->name('rekam-medis.show');

    // Jadwal
    Route::get('jadwal', [Dokter\JadwalController::class, 'index'])->name('jadwal.index');
    Route::post('jadwal', [Dokter\JadwalController::class, 'store'])->name('jadwal.store');
    Route::delete('jadwal/{jadwal}', [Dokter\JadwalController::class, 'destroy'])->name('jadwal.destroy');
    Route::patch('jadwal/{jadwal}/toggle', [Dokter\JadwalController::class, 'toggle'])->name('jadwal.toggle');

    // Profil
    Route::get('profil', [Dokter\ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('profil', [Dokter\ProfilController::class, 'update'])->name('profil.update');
});

// ─── Pasien ────────────────────────────────────────────────────────────────
Route::prefix('pasien')->name('pasien.')->middleware(['auth', 'role:pasien'])->group(function () {
    Route::get('/dashboard', [Pasien\DashboardController::class, 'index'])->name('dashboard');

    // Appointments (note: create must come before {appointment} wildcard)
    Route::get('appointments/buat', [Pasien\AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('appointments', [Pasien\AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('appointments', [Pasien\AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('appointments/{appointment}', [Pasien\AppointmentController::class, 'show'])->name('appointments.show');
    Route::patch('appointments/{appointment}/cancel', [Pasien\AppointmentController::class, 'cancel'])->name('appointments.cancel');

    // Rekam Medis (read-only)
    Route::get('rekam-medis', [Pasien\RekamMedisController::class, 'index'])->name('rekam-medis.index');
    Route::get('rekam-medis/{rekamMedis}', [Pasien\RekamMedisController::class, 'show'])->name('rekam-medis.show');

    // Profil
    Route::get('profil', [Pasien\ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('profil', [Pasien\ProfilController::class, 'update'])->name('profil.update');

    // AJAX helper: ambil jadwal berdasarkan dokter
    Route::get('get-jadwal/{dokter}', [Pasien\AppointmentController::class, 'getJadwal'])->name('get-jadwal');
});
