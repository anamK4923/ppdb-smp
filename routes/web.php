<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\FormulirController;
use App\Http\Controllers\DataPendaftarController;
use App\Http\Controllers\Admin\PendaftarController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Student\PendaftaranController;
use App\Http\Controllers\Student\StatusController;
use App\Http\Controllers\Student\PengumumanController as StudentPengumumanController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Dashboard dispatcher
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

    // Pendaftar routes (hanya untuk verifikasi)
    Route::get('/pendaftar', [PendaftarController::class, 'index'])->name('pendaftar.index');
    Route::get('/pendaftar/{id}', [PendaftarController::class, 'show'])->name('pendaftar.show');
    Route::post('/pendaftar/{id}/verifikasi', [PendaftarController::class, 'verifikasi'])->name('pendaftar.verifikasi');
    Route::get('/pendaftar/{id}/cetak', [PendaftarController::class, 'cetak'])->name('pendaftar.cetak');
    Route::get('/pendaftar/export/pdf', [PendaftarController::class, 'exportPdf'])->name('pendaftar.export');
    Route::delete('/pendaftar/{id}', [PendaftarController::class, 'destroy'])->name('pendaftar.destroy');

    // Pengumuman routes (untuk kelola hasil seleksi)
    Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
    Route::post('/pengumuman/batch', [PengumumanController::class, 'updateBatch'])->name('pengumuman.batch');
    Route::post('/pengumuman/{id}/update', [PengumumanController::class, 'updateSingle'])->name('pengumuman.update');
    Route::delete('/pengumuman/{id}/hapus', [PengumumanController::class, 'hapusPengumuman'])->name('pengumuman.hapus');
    Route::get('/pengumuman/export/pdf', [PengumumanController::class, 'exportPengumuman'])->name('pengumuman.export');

    // User Management routes
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    Route::get('/users/{id}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{id}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
});

// Student routes
Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'student'])->name('dashboard');

    // Pendaftaran routes
    Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran.index');
    Route::post('/pendaftaran', [PendaftaranController::class, 'store'])->name('pendaftaran.store');

    // Status routes
    Route::get('/status', [StatusController::class, 'index'])->name('status.index');

    // Pengumuman routes
    Route::get('/pengumuman', [StudentPengumumanController::class, 'index'])->name('pengumuman.index');
    Route::get('/pengumuman/hasil', [StudentPengumumanController::class, 'lihatHasil'])->name('pengumuman.hasil');
    Route::get('/pengumuman/cetak', [StudentPengumumanController::class, 'cetakHasil'])->name('pengumuman.cetak');
});

// Legacy routes (untuk backward compatibility)
Route::middleware(['auth', 'student'])->group(function () {
    Route::get('/student/formulir-pendaftaran', [FormulirController::class, 'index'])->name('student.formulir-pendaftaran');
    Route::post('/student/formulir-pendaftaran', [FormulirController::class, 'store'])->name('student.formulir-pendaftaran.store');
    Route::get('/student/kelola-data-pendaftar', [DataPendaftarController::class, 'index'])->name('student.kelola-data-pendaftar');
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
