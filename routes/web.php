<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataPendaftarController;
use App\Http\Controllers\FormulirController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::middleware(['admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'indexAdmin'])->name('dashboard.admin');
    Route::get('/admin/data-pendaftar', [DataPendaftarController::class, 'index'])->name('admin.data-pendaftar');
});

Route::middleware(['student'])->group(function () {
    Route::get('/dashboard-student', [DashboardController::class, 'indexStudent'])->name('dashboard.student');
    Route::get('/student/formulir-pendaftaran', [FormulirController::class, 'index'])->name('formulir.student');
});

Route::get(
    'notifications/get',
    [NotificationsController::class, 'getNotificationsData']
)->name('notifications.get');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified   '])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
