<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\AlumniController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/password-request', [AuthController::class, 'requestReset'])->name('password.request.admin');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::prefix('admin')->middleware('cek.admin')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('admin.dashboard');

    // Route khusus untuk sinkronisasi data alumni ke tabel users
    Route::post('/akun/sync', [AkunController::class, 'syncAlumni'])->name('admin.akun.sync');
    // Route khusus untuk reset password akun
    Route::post('/akun/{akun}/reset-password', [AkunController::class, 'resetPasswordToDefault'])->name('admin.akun.resetPassword');

    // Resource route untuk Kelola Akun
    Route::resource('/akun', AkunController::class)->names([
        'index'   => 'admin.akun.index',
        'create'  => 'admin.akun.create',
        'store'   => 'admin.akun.store',
        'show'    => 'admin.akun.show',
        'edit'    => 'admin.akun.edit',
        'update'  => 'admin.akun.update',
        'destroy' => 'admin.akun.destroy',
    ]);

    // Route khusus untuk import & export template (harus sebelum resource route)
    Route::post('/alumni/import', [AlumniController::class, 'import'])->name('admin.alumni.import');
    Route::get('/alumni/export-template', [AlumniController::class, 'exportTemplate'])->name('admin.alumni.exportTemplate');

    // Resource route untuk Kelola Data Alumni
    Route::resource('/alumni', AlumniController::class)->parameters([
        'alumni' => 'alumnus',
    ])->names([
        'index'   => 'admin.alumni.index',
        'create'  => 'admin.alumni.create',
        'store'   => 'admin.alumni.store',
        'show'    => 'admin.alumni.show',
        'edit'    => 'admin.alumni.edit',
        'update'  => 'admin.alumni.update',
        'destroy' => 'admin.alumni.destroy',
    ]);

    Route::resource('/lowongan', \App\Http\Controllers\LowonganController::class)->names([
        'index'   => 'admin.lowongan.index',
        'create'  => 'admin.lowongan.create',
        'store'   => 'admin.lowongan.store',
        'show'    => 'admin.lowongan.show',
        'edit'    => 'admin.lowongan.edit',
        'update'  => 'admin.lowongan.update',
        'destroy' => 'admin.lowongan.destroy',
    ]);
    Route::resource('/kuesioner', \App\Http\Controllers\KuesionerController::class)->names([
        'index'   => 'admin.kuesioner.index',
        'create'  => 'admin.kuesioner.create',
        'store'   => 'admin.kuesioner.store',
        'show'    => 'admin.kuesioner.show',
        'edit'    => 'admin.kuesioner.edit',
        'update'  => 'admin.kuesioner.update',
        'destroy' => 'admin.kuesioner.destroy',
    ]);

    Route::get('/laporan', [\App\Http\Controllers\LaporanController::class, 'index'])->name('admin.laporan.index');
    Route::get('/laporan/cetak', [\App\Http\Controllers\LaporanController::class, 'cetak'])->name('admin.laporan.cetak');
    Route::get('/laporan/download', [\App\Http\Controllers\LaporanController::class, 'download'])->name('admin.laporan.download');
});

// Alumni Routes
Route::prefix('alumni')->middleware('cek.user')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AlumniDashboardController::class, 'index'])->name('alumni.dashboard');
    Route::get('/kuesioner', [\App\Http\Controllers\AlumniKuesionerController::class, 'index'])->name('alumni.kuesioner.index');
    Route::post('/kuesioner', [\App\Http\Controllers\AlumniKuesionerController::class, 'store'])->name('alumni.kuesioner.store');
    Route::get('/lowongan', [\App\Http\Controllers\AlumniLowonganController::class, 'index'])->name('alumni.lowongan.index');
    Route::get('/profil', [\App\Http\Controllers\AlumniProfilController::class, 'index'])->name('alumni.profil.index');
    Route::put('/profil', [\App\Http\Controllers\AlumniProfilController::class, 'update'])->name('alumni.profil.update');
});
