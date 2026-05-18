<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\StatistikController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DomainMonitorController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Desa\DashboardController as DesaDashboard;
use App\Http\Controllers\Desa\SettingController;
use App\Http\Controllers\Frontend\DesaController as FrontendDesa;
use App\Http\Controllers\AntikorupsiController;
use App\Http\Controllers\Admin\AntikorupsiSettingController;
use App\Http\Controllers\Desa\AntikorupsiDesaController;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('desa.dashboard');
    }
    return redirect('/login');
})->middleware(['auth'])->name('dashboard');

// ==========================================
// ROUTE ADMIN (Tanpa Pengecekan Role Khusus)
// ==========================================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/entri/{desa_id}', [StatistikController::class, 'entri'])->name('entri');
    Route::post('/simpan', [StatistikController::class, 'simpan'])->name('simpan');
    Route::post('/tahun-baru', [StatistikController::class, 'storeTahun'])->name('tahun.store');
    Route::post('/import', [StatistikController::class, 'import'])->name('import');
    Route::get('/download-template', [StatistikController::class, 'downloadTemplate'])->name('download-template');
    Route::get('/export-agama', [StatistikController::class, 'exportAgama'])->name('export.agama');
    Route::get('/status-laporan', [StatistikController::class, 'index'])->name('status-laporan');
});

// ==========================================
// ROUTE ADMIN (Khusus Role: Admin)
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [StatistikController::class, 'dashboard'])->name('dashboard');
    Route::get('/desa', [StatistikController::class, 'index'])->name('index');
    Route::get('/domain-monitor', [DomainMonitorController::class, 'index'])->name('domain.monitor');

    Route::resource('kategori', KategoriController::class)->only(['index', 'store', 'destroy']);
    Route::patch('kategori/{id}/toggle', [KategoriController::class, 'toggleStatus'])->name('kategori.toggle');

    Route::post('kategori/{id}/add-indicator', [KategoriController::class, 'addIndicator'])->name('kategori.add-indicator');
    Route::patch('indikator/{id}', [KategoriController::class, 'updateIndicator'])->name('indikator.update');
    Route::delete('indikator/{id}', [KategoriController::class, 'destroyIndicator'])->name('indikator.destroy');
    
    Route::patch('indikator/{id}/toggle', [KategoriController::class, 'toggleIndicatorStatus'])->name('indicator.toggle');
    
    Route::get('/desa/{desa_id}/atur-form', [StatistikController::class, 'aturForm'])->name('atur-form');
    Route::post('/desa/{desa_id}/atur-form', [StatistikController::class, 'simpanAturForm'])->name('atur-form.simpan');

    Route::resource('users', UserController::class);

    Route::get('/setting-antikorupsi', [AntikorupsiSettingController::class, 'index'])->name('antikorupsi.setting');
    Route::post('/setting-antikorupsi/toggle/{id}', [AntikorupsiSettingController::class, 'toggleStatus'])->name('antikorupsi.toggle');
    Route::get('/antikorupsi-setting', [AntikorupsiSettingController::class, 'index'])->name('antikorupsi.setting');
});

// ==========================================
// ROUTE DESA (Khusus Role: Desa)
// ==========================================
Route::middleware(['auth', 'role:desa'])->prefix('desa')->name('desa.')->group(function () {
    Route::get('/dashboard', [DesaDashboard::class, 'index'])->name('dashboard');
    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::patch('/settings', [DesaDashboard::class, 'updateBranding'])->name('settings.update');

    // --- FITUR ANTIKORUPSI (INPUT DESA) ---
    // Route ini disiapkan untuk panel input link Drive oleh desa
    Route::get('/antikorupsi', [AntikorupsiDesaController::class, 'index'])->name('antikorupsi.index');
    Route::put('/antikorupsi/update', [AntikorupsiDesaController::class, 'update'])->name('antikorupsi.update');
    Route::post('/antikorupsi/store', [AntikorupsiDesaController::class, 'store'])->name('antikorupsi.store');
    Route::delete('/antikorupsi/{id}', [AntikorupsiDesaController::class, 'destroy'])->name('antikorupsi.destroy');
    Route::put('/antikorupsi/edit/{id}', [AntikorupsiDesaController::class, 'editData'])->name('antikorupsi.edit');
    Route::resource('master-grup-antikorupsi', \App\Http\Controllers\Desa\MasterGrupAntikorupsiController::class)->except(['create', 'show', 'edit']);
});

// ==========================================
// ROUTE UMUM / PROFIL
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==========================================
// ROUTE PUBLIK (Frontend)
// ==========================================
Route::get('/desa/{slug}', [FrontendDesa::class, 'profilDesa'])->name('desa.profil');

// Halaman Publik Desa Antikorupsi
Route::get('/desa-antikorupsi', [AntikorupsiController::class, 'index'])->name('antikorupsi.index');
require __DIR__.'/auth.php';