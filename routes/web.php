<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\InstitusiController;
use App\Http\Controllers\KejuaraanController;
use App\Http\Controllers\PrestasiBelmawaController;
use App\Http\Controllers\PrestasiMandiriController;
use App\Http\Controllers\RekapitulasiController;
use App\Http\Controllers\RekognisiController;
use App\Http\Controllers\SertifikasiController;
use App\Http\Controllers\LogoProdiController;
use App\Http\Controllers\TemplateLpjController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'login')->name('login')->middleware('guest');
    Route::get('/login', 'login')->middleware('guest');
    Route::post('/login', 'authenticate')->name('login.post')->middleware('guest');
    Route::get('/register', 'register')->name('register')->middleware('guest');
    Route::post('/register', 'storeRegister')->name('register.post')->middleware('guest');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth');
});

Route::middleware(['auth', 'checkrole'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Rekapitulasi & Pelaporan Routes
    Route::get('/rekapitulasi', [RekapitulasiController::class, 'index'])->name('rekapitulasi.index');
    Route::get('/rekapitulasi/pdf', [RekapitulasiController::class, 'exportPdf'])->name('rekapitulasi.pdf');
    Route::get('/rekapitulasi/excel', [RekapitulasiController::class, 'exportExcel'])->name('rekapitulasi.excel');

    // Profile Routes
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile.show');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    // Change Password Routes
    Route::get('/change-password', [AuthController::class, 'editPassword'])->name('password.edit');
    Route::put('/change-password', [AuthController::class, 'updatePassword'])->name('password.update');

    // User Management Resource Routes
    Route::resource('users', UserController::class);

    // Dosen Resource & Import Routes
    Route::get('dosen/template/download', [DosenController::class, 'downloadTemplate'])->name('dosen.template');
    Route::post('dosen/import', [DosenController::class, 'import'])->name('dosen.import');
    Route::resource('dosen', DosenController::class);

    // Prestasi Belmawa Resource Routes
    Route::resource('prestasi-belmawa', PrestasiBelmawaController::class);

    // Prestasi Mandiri Resource Routes
    Route::get('prestasi-mandiri/template-lpj/download', [PrestasiMandiriController::class, 'downloadTemplateLpj'])->name('prestasi-mandiri.template-lpj');
    Route::get('prestasi-mandiri/{prestasi_mandiri}/download-lpj', [PrestasiMandiriController::class, 'downloadUploadedLpj'])->name('prestasi-mandiri.download-lpj');
    Route::resource('prestasi-mandiri', PrestasiMandiriController::class);

    // Rekognisi Resource Routes
    Route::resource('rekognisi', RekognisiController::class);

    // Sertifikasi Resource Routes
    Route::resource('sertifikasi', SertifikasiController::class);

    // Kejuaraan / Ajang / Lomba Resource Routes
    Route::resource('kejuaraan', KejuaraanController::class);

    // Institusi Resource Routes
    Route::resource('institusi', InstitusiController::class);

    // Logo Prodi Resource Routes
    Route::resource('logo-prodi', LogoProdiController::class);

    // Template LPJ Resource Routes
    Route::get('template-lpj/{template_lpj}/download', [TemplateLpjController::class, 'download'])->name('template-lpj.download');
    Route::resource('template-lpj', TemplateLpjController::class);
});