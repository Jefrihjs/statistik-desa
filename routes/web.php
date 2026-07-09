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
use App\Http\Controllers\Embed\PpidEmbedController;
use App\Http\Controllers\Embed\PpidPermohonanEmbedController;

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

    Route::resource('kategori', KategoriController::class)->only(['index', 'store', 'destroy']);
    Route::patch('kategori/{id}/toggle', [KategoriController::class, 'toggleStatus'])->name('kategori.toggle');

    Route::post('kategori/{id}/add-indicator', [KategoriController::class, 'addIndicator'])->name('kategori.add-indicator');
    Route::patch('indikator/{id}', [KategoriController::class, 'updateIndicator'])->name('indikator.update');
    Route::delete('indikator/{id}', [KategoriController::class, 'destroyIndicator'])->name('indikator.destroy');

    Route::patch('indikator/{id}/toggle', [KategoriController::class, 'toggleIndicatorStatus'])->name('indicator.toggle');

    Route::get('/desa/{desa_id}/atur-form', [StatistikController::class, 'aturForm'])->name('atur-form');
    Route::post('/desa/{desa_id}/atur-form', [StatistikController::class, 'simpanAturForm'])->name('atur-form.simpan');

    Route::resource('users', UserController::class);

    // --- FITUR ANTIKORUPSI ADMIN ---
    Route::get('/antikorupsi-setting', [AntikorupsiSettingController::class, 'index'])->name('antikorupsi.setting');
    Route::post('/antikorupsi-setting/toggle/{id}', [AntikorupsiSettingController::class, 'toggleStatus'])->name('antikorupsi.toggle');

    // --- MONITORING ---
    Route::get('/domain-monitor', [DomainMonitorController::class, 'index'])->name('domain.monitor');
    Route::get('/ssl-monitor', [DomainMonitorController::class, 'sslMonitor'])->name('ssl.monitor');
    Route::get('/skm-monitor', [\App\Http\Controllers\Admin\SkmMonitorController::class, 'index'])->name('skm.monitor');
    Route::get('/ppid-monitor', [\App\Http\Controllers\Admin\PpidMonitorController::class, 'index'])->name('ppid.monitor');

    // --- MODUL & LOG ---
    Route::get('/module-setting', [\App\Http\Controllers\Admin\ModuleSettingController::class, 'index'])->name('module.setting');
    Route::post('/module-setting/{user}/toggle', [\App\Http\Controllers\Admin\ModuleSettingController::class, 'toggle'])->name('module.toggle');
    Route::get('/activity-logs', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity.logs');
});

// ==========================================
// ROUTE DESA (Khusus Role: Desa)
// ==========================================
Route::middleware(['auth', 'role:desa'])->prefix('desa')->name('desa.')->group(function () {
    Route::get('/dashboard', [DesaDashboard::class, 'index'])->name('dashboard');
    Route::get('/statistik', [DesaDashboard::class, 'statistik'])->name('statistik');

    // --- BRANDING (warna, header) ---
    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::patch('/settings', [DesaDashboard::class, 'updateBranding'])->name('settings.update');

    // --- PENGATURAN DESA (identitas, pejabat, tampilan publik) ---
    Route::get('/pengaturan', [\App\Http\Controllers\Desa\PengaturanDesaController::class, 'edit'])->name('pengaturan.edit');
    Route::post('/pengaturan', [\App\Http\Controllers\Desa\PengaturanDesaController::class, 'update'])->name('pengaturan.update');

    // --- FITUR ANTIKORUPSI ---
    Route::get('/antikorupsi', [AntikorupsiDesaController::class, 'index'])->name('antikorupsi.index');
    Route::put('/antikorupsi/update', [AntikorupsiDesaController::class, 'update'])->name('antikorupsi.update');
    Route::post('/antikorupsi/reorder', [AntikorupsiDesaController::class, 'reorder'])->name('antikorupsi.reorder');
    Route::post('/antikorupsi/update-link', [AntikorupsiDesaController::class, 'updateLink'])->name('antikorupsi.update.link');
    Route::post('/antikorupsi/store', [AntikorupsiDesaController::class, 'store'])->name('antikorupsi.store');
    Route::delete('/antikorupsi/{id}', [AntikorupsiDesaController::class, 'destroy'])->name('antikorupsi.destroy');
    Route::put('/antikorupsi/edit/{id}', [AntikorupsiDesaController::class, 'editData'])->name('antikorupsi.edit');
    Route::resource('master-grup-antikorupsi', \App\Http\Controllers\Desa\MasterGrupAntikorupsiController::class)->except(['create', 'show', 'edit']);

    // --- FITUR PPID ---
    Route::get('/ppid', [\App\Http\Controllers\Desa\PpidDesaController::class, 'index'])->name('ppid.index');
    Route::get('/ppid/dip', [\App\Http\Controllers\Desa\PpidDipController::class, 'index'])->name('ppid.dip.index');
    Route::post('/ppid/dip', [\App\Http\Controllers\Desa\PpidDipController::class, 'store'])->name('ppid.dip.store');
    Route::put('/ppid/dip/{id}', [\App\Http\Controllers\Desa\PpidDipController::class, 'update'])->name('ppid.dip.update');
    Route::delete('/ppid/dip/{id}', [\App\Http\Controllers\Desa\PpidDipController::class, 'destroy'])->name('ppid.dip.destroy');

    Route::get('/ppid/permohonan', [\App\Http\Controllers\Desa\PpidPermohonanController::class, 'index'])->name('ppid.permohonan.index');
    Route::get('/ppid/permohonan/{id}', [\App\Http\Controllers\Desa\PpidPermohonanController::class, 'show'])->name('ppid.permohonan.show');
    Route::delete('/ppid/permohonan/{id}', [\App\Http\Controllers\Desa\PpidPermohonanController::class, 'destroy'])->name('ppid.permohonan.destroy');
    Route::post('/ppid/permohonan/{id}/update-status', [\App\Http\Controllers\Desa\PpidPermohonanController::class, 'updateStatus'])->name('ppid.permohonan.update_status');
    Route::post('/ppid/permohonan/{id}/tidak-lengkap', [\App\Http\Controllers\Desa\PpidPermohonanController::class, 'tidakLengkap'])->name('ppid.permohonan.tidak_lengkap');
    Route::post('/ppid/permohonan/{id}/upload-selesai', [\App\Http\Controllers\Desa\PpidPermohonanController::class, 'uploadSelesai'])->name('ppid.permohonan.upload_selesai');
    Route::post('/ppid/permohonan/{id}/pemberitahuan', [\App\Http\Controllers\Desa\PpidPermohonanController::class, 'storePemberitahuan'])->name('ppid.permohonan.pemberitahuan');
    Route::get('/ppid/permohonan/{id}/cetak-pemberitahuan', [\App\Http\Controllers\Desa\PpidPermohonanController::class, 'cetakPemberitahuan'])->name('ppid.permohonan.cetak_pemberitahuan');
    Route::get('/ppid/permohonan/{id}/cetak-sk-penolakan', [\App\Http\Controllers\Desa\PpidPermohonanController::class, 'cetakSkPenolakan'])->name('ppid.permohonan.cetak_sk_penolakan');
    Route::get('/ppid/permohonan/{id}/cetak-tidak-lengkap', [\App\Http\Controllers\Desa\PpidPermohonanController::class, 'cetakTidakLengkap'])->name('ppid.permohonan.cetak_tidak_lengkap');
    Route::post('/ppid/permohonan/{id}/keberatan/tanggapan', [\App\Http\Controllers\Desa\PpidPermohonanController::class, 'tanggapiKeberatan'])->name('ppid.permohonan.keberatan.tanggapan');

    Route::get('/ppid/keberatan', [\App\Http\Controllers\Desa\PpidKeberatanController::class, 'index'])->name('ppid.keberatan.index');
    Route::get('/ppid/keberatan/{id}', [\App\Http\Controllers\Desa\PpidKeberatanController::class, 'show'])->name('ppid.keberatan.show');

    Route::get('/ppid/laporan', [\App\Http\Controllers\Desa\PpidLaporanController::class, 'index'])->name('ppid.laporan.index');
    Route::get('/ppid/laporan/cetak', [\App\Http\Controllers\Desa\PpidLaporanController::class, 'cetak'])->name('ppid.laporan.cetak');

    // --- FITUR SKM ---
    Route::get('/skm', [\App\Http\Controllers\Desa\SkmDesaController::class, 'index'])->name('skm.index');
    Route::post('/skm/questions', [\App\Http\Controllers\Desa\SkmDesaController::class, 'storeQuestion'])->name('skm.questions.store');
    Route::put('/skm/questions/{id}', [\App\Http\Controllers\Desa\SkmDesaController::class, 'updateQuestion'])->name('skm.questions.update');
    Route::delete('/skm/questions/{id}', [\App\Http\Controllers\Desa\SkmDesaController::class, 'destroyQuestion'])->name('skm.questions.destroy');

    Route::post('/skm/rekomendasi', [\App\Http\Controllers\Desa\SkmDesaController::class, 'storeRekomendasi'])->name('skm.rekomendasi.store');
    Route::patch('/skm/rekomendasi/{id}/toggle', [\App\Http\Controllers\Desa\SkmDesaController::class, 'toggleRekomendasi'])->name('skm.rekomendasi.toggle');
    Route::delete('/skm/rekomendasi/{id}', [\App\Http\Controllers\Desa\SkmDesaController::class, 'destroyRekomendasi'])->name('skm_rekomendasi.destroy');

    // --- MONITORING WEBSITE ---
    Route::get('/domain', [\App\Http\Controllers\Desa\DomainDesaController::class, 'index'])->name('domain.index');
    Route::get('/ssl', [\App\Http\Controllers\Desa\SslDesaController::class, 'index'])->name('ssl.index');

    Route::get('/aduan', [\App\Http\Controllers\Desa\AduanDesaController::class, 'index'])->name('aduan.index');
    Route::patch('/aduan/{aduan}/status', [\App\Http\Controllers\Desa\AduanDesaController::class, 'updateStatus'])->name('aduan.update-status');
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
Route::get('/desa/{slug}/antikorupsi', [AntikorupsiController::class, 'index'])->name('antikorupsi.index');

// --- Embed PPID ---
Route::get('/embed/ppid/{slug}/permohonan/success/{kode}', [PpidPermohonanEmbedController::class, 'success'])->name('embed.ppid.permohonan.success');
Route::get('/embed/ppid/{slug}/permohonan/{kode}/bukti', [PpidPermohonanEmbedController::class, 'cetakBukti'])->name('embed.ppid.permohonan.bukti');
Route::get('/embed/ppid/{slug}/monitoring', [PpidPermohonanEmbedController::class, 'monitoring'])->name('embed.ppid.permohonan.monitoring');
Route::post('/embed/ppid/{slug}/monitoring', [PpidPermohonanEmbedController::class, 'cekMonitoring'])->name('embed.ppid.permohonan.monitoring.cek');
Route::get('/embed/ppid/{slug}/monitoring/{kode}', [PpidPermohonanEmbedController::class, 'hasilMonitoring'])->name('embed.ppid.permohonan.monitoring.hasil');
Route::get('/embed/ppid/{slug}/permohonan', [PpidPermohonanEmbedController::class, 'create'])->name('embed.ppid.permohonan');
Route::post('/embed/ppid/{slug}/permohonan', [PpidPermohonanEmbedController::class, 'store'])->name('embed.ppid.permohonan.store');
Route::get('/embed/ppid/{slug}/monitoring/{kode}/pemberitahuan', [PpidPermohonanEmbedController::class, 'cetakPemberitahuan'])->name('embed.ppid.permohonan.pemberitahuan');
Route::get('/embed/ppid/{slug}/monitoring/{kode}/sk-penolakan', [PpidPermohonanEmbedController::class, 'cetakSkPenolakan'])->name('embed.ppid.permohonan.sk_penolakan');
Route::post('/embed/ppid/{slug}/monitoring/{kode}/keberatan', [PpidPermohonanEmbedController::class, 'storeKeberatan'])->name('embed.ppid.permohonan.keberatan.store');
Route::get('/embed/ppid/{slug}/monitoring/{kode}/tidak-lengkap', [PpidPermohonanEmbedController::class, 'cetakTidakLengkap'])->name('embed.ppid.permohonan.tidak_lengkap');
Route::get('/embed/ppid/{slug}/{kategori}', [PpidEmbedController::class, 'dip'])->name('embed.ppid.dip');
Route::get('/embed/ppid/{slug}/{kategori}', [App\Http\Controllers\PublicPpidController::class, 'embed'])->name('public.ppid.embed');

// --- SKM Publik ---
Route::get('/skm/{slug}', [\App\Http\Controllers\Public\SkmPublicController::class, 'create'])->name('public.skm.create');
Route::post('/skm/{slug}', [\App\Http\Controllers\Public\SkmPublicController::class, 'store'])->name('public.skm.store');
Route::get('/skm/{slug}/terima-kasih', [\App\Http\Controllers\Public\SkmPublicController::class, 'success'])->name('public.skm.success');
Route::get('/skm/{slug}/hasil', [\App\Http\Controllers\Public\SkmPublicController::class, 'hasil'])->name('public.skm.hasil');
Route::get('/skm/{slug}/hasil/{id}/cetak', [\App\Http\Controllers\Public\SkmPublicController::class, 'cetakHasil'])->name('public.skm.cetak');
Route::get('/skm/{slug}/hasil/{id}/responden', [\App\Http\Controllers\Public\SkmPublicController::class, 'detailResponden'])->name('public.skm.responden');

// --- Aduan Publik ---
Route::get('/aduan/{slug}', [\App\Http\Controllers\Public\AduanPublicController::class, 'create'])->name('public.aduan.create');
Route::post('/aduan/{slug}', [\App\Http\Controllers\Public\AduanPublicController::class, 'store'])->name('public.aduan.store');
Route::get('/aduan/{slug}/selesai/{kode}', [\App\Http\Controllers\Public\AduanPublicController::class, 'success'])->name('public.aduan.success');
Route::get('/aduan/{slug}/cek-status', [\App\Http\Controllers\Public\AduanPublicController::class, 'checkStatus'])->name('public.aduan.check-status');
Route::post('/aduan/{slug}/cek-status', [\App\Http\Controllers\Public\AduanPublicController::class, 'showStatus'])->name('public.aduan.show-status');

require __DIR__.'/auth.php';