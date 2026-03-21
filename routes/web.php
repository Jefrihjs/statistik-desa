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

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Statistik Desa Belitung Timur (SiCANTIK)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Redirect setelah login berdasarkan Role
Route::get('/dashboard', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('desa.dashboard');
    }
    return redirect('/login');
})->middleware(['auth'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Route SHARE (Dapat diakses Admin Kabupaten & Operator Desa)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Input data statistik
    Route::get('/entri/{desa_id}', [StatistikController::class, 'entri'])->name('entri');
    Route::post('/simpan', [StatistikController::class, 'simpan'])->name('simpan');
    Route::post('/tahun-baru', [StatistikController::class, 'storeTahun'])->name('tahun.store');

    // Import & export
    Route::post('/import', [StatistikController::class, 'import'])->name('import');
    Route::get('/download-template', [StatistikController::class, 'downloadTemplate'])->name('download-template');
    Route::get('/export-agama', [StatistikController::class, 'exportAgama'])->name('export.agama');
    Route::get('/status-laporan', [StatistikController::class, 'index'])->name('status-laporan');
});

/*
|--------------------------------------------------------------------------
| Route KHUSUS ADMIN KABUPATEN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard & Monitoring
    Route::get('/dashboard', [StatistikController::class, 'dashboard'])->name('dashboard');
    Route::get('/desa', [StatistikController::class, 'index'])->name('index');
    Route::get('/domain-monitor', [DomainMonitorController::class, 'index'])->name('domain.monitor');

    /* --- Manajemen Kategori & Indikator --- */
    // Route Utama Kategori
    Route::resource('kategori', KategoriController::class)->only(['index', 'store', 'destroy']);
    Route::patch('kategori/{id}/toggle', [KategoriController::class, 'toggleStatus'])->name('kategori.toggle');

    // Route Operasi Indikator
    Route::post('kategori/{id}/add-indicator', [KategoriController::class, 'addIndicator'])->name('kategori.add-indicator');
    Route::patch('indikator/{id}', [KategoriController::class, 'updateIndicator'])->name('indikator.update');
    Route::delete('indikator/{id}', [KategoriController::class, 'destroyIndicator'])->name('indikator.destroy');
    
    // Toggle Aktif/Nonaktif Indikator (Fungsi Baru)
    Route::patch('indikator/{id}/toggle', [KategoriController::class, 'toggleIndicatorStatus'])->name('indicator.toggle');
    
    // Pilihan sembunyi/tampil Kategori & Indikator per Desa
    Route::get('/desa/{desa_id}/atur-form', [StatistikController::class, 'aturForm'])->name('atur-form');
    Route::post('/desa/{desa_id}/atur-form', [StatistikController::class, 'simpanAturForm'])->name('atur-form.simpan');

    /* --- Manajemen User --- */
    Route::resource('users', UserController::class);
});

/*
|--------------------------------------------------------------------------
| Route KHUSUS OPERATOR DESA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:desa'])->prefix('desa')->name('desa.')->group(function () {
    Route::get('/dashboard', [DesaDashboard::class, 'index'])->name('dashboard');
    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::patch('/settings', [DesaDashboard::class, 'updateBranding'])->name('settings.update');
});

/*
|--------------------------------------------------------------------------
| Route Profile & Frontend
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Profil Desa (Akses Publik)
Route::get('/desa/{slug}', [FrontendDesa::class, 'profilDesa'])->name('desa.profil');

require __DIR__.'/auth.php';