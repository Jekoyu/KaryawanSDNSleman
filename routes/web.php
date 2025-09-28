<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataKaryawanController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\RiwayatPekerjaanController;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/healthz', fn () => response('OK', 200));
Route::get('/debug-headers', function (\Illuminate\Http\Request $request) {
    return response()->json($request->headers->all());
});


Route::get('/', [LoginController::class, 'showLogin'])->name('login');
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);

Route::get('/dashboard', [DashboardController::class, 'index']);

// Data Karyawan CRUD routes
Route::resource('data-karyawan', DataKaryawanController::class);

// Dokumen CRUD routes
Route::resource('dokumen', DokumenController::class);
Route::get('/dokumen/{id}/download', [DokumenController::class, 'download'])->name('dokumen.download');

// Kehadiran specific routes (must be before resource routes)
Route::get('/kehadiran/date/{tanggal}', [KehadiranController::class, 'getKehadiranByDate'])->name('kehadiran.by-date');
Route::get('/kehadiran/monthly-report', [KehadiranController::class, 'getMonthlyReport'])->name('kehadiran.monthly-report');
Route::post('/kehadiran/set-holiday', [KehadiranController::class, 'setHoliday'])->name('kehadiran.set-holiday');

// Kehadiran CRUD routes
Route::resource('kehadiran', KehadiranController::class);

// Riwayat Pekerjaan CRUD routes
Route::resource('riwayat-pekerjaan', RiwayatPekerjaanController::class);
Route::get('/riwayat-pekerjaan/karyawan/{id}', [RiwayatPekerjaanController::class, 'getByKaryawan'])->name('riwayat-pekerjaan.by-karyawan');

// Karyawan routes
Route::get('/profil-saya', function () {
    if (! session()->has('user_id')) {
        return redirect('/login');
    }
    return view('pages.profil-saya');
});

Route::get('/dokumen-saya', function () {
    if (! session()->has('user_id')) {
        return redirect('/login');
    }
    return view('pages.dokumen-saya');
});

Route::get('/kehadiran-saya', function () {
    if (! session()->has('user_id')) {
        return redirect('/login');
    }
    return view('pages.kehadiran-saya');
});
