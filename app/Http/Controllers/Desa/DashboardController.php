<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    /**
     * Halaman Hub Utama TARSIUS
     */
    public function index()
    {
        $desa = auth()->user()->desa;
        abort_if(!$desa, 404, 'Akun Anda belum terhubung dengan data desa mana pun.');

        $tracker = DB::table('domain_trackers')
            ->where('desa_id', $desa->id)
            ->first();

        $sslInfo = [
            'status' => 'error',
            'pesan' => 'Belum Terdata',
            'hari' => 0
        ];

        if ($tracker && $tracker->domain_name) {
            $cacheKey = 'ssl_info_' . md5($tracker->domain_name);
            $sslInfo = Cache::remember($cacheKey, now()->addHours(6), function () use ($tracker) {
                return $this->getRealSslDays($tracker->domain_name);
            });
        }

        return view('desa.dashboard', compact('desa', 'tracker', 'sslInfo'));
    }

    /**
     * Helper Function: Mendeteksi sisa hari aktif sertifikat SSL (HTTPS)
     */
    private function getRealSslDays($domain)
    {
        try {
            $cleanDomain = preg_replace('/^https?:\/\//i', '', rtrim($domain, '/'));
            $cleanDomain = parse_url('https://' . $cleanDomain, PHP_URL_HOST) ?? $cleanDomain;

            $streamContext = stream_context_create([
                "ssl" => [
                    "capture_peer_cert" => true,
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ]
            ]);

            $client = @stream_socket_client(
                "ssl://" . $cleanDomain . ":443",
                $errno,
                $errstr,
                1.5,
                STREAM_CLIENT_CONNECT,
                $streamContext
            );

            if (!$client) {
                return ['status' => 'error', 'pesan' => 'Tidak Terkoneksi', 'hari' => 0];
            }

            $params = stream_context_get_params($client);
            $cert = openssl_x509_parse($params["options"]["ssl"]["peer_certificate"]);
            fclose($client);

            if (!$cert || !isset($cert['validTo_time_t'])) {
                return ['status' => 'error', 'pesan' => 'SSL Invalid', 'hari' => 0];
            }

            $expiryTimestamp = $cert['validTo_time_t'];
            $sisaDetik = $expiryTimestamp - time();
            $sisaHari = (int) floor($sisaDetik / (60 * 60 * 24));

            if ($sisaHari <= 0) {
                return ['status' => 'expired', 'pesan' => 'Expired', 'hari' => $sisaHari];
            }

            return [
                'status' => 'active',
                'pesan' => $sisaHari . ' Hari Lagi',
                'hari' => $sisaHari
            ];

        } catch (\Throwable $e) {
            return ['status' => 'error', 'pesan' => 'Gagal Cek', 'hari' => 0];
        }
    }

    /**
     * Halaman Sub-Layanan Khusus Statistik Sektoral Desa
     */
    public function statistik(Request $request)
    {
        $desa = auth()->user()->desa;
        abort_if(!$desa, 404);

        $tahunAktif = $request->input('tahunAktif', date('Y') - 1);

        $statusPengisian = \App\Models\Category::where('is_active', 1)
            ->withCount(['indicators as total_indikator'])
            ->withCount(['indicators as terisi' => function($q) use ($desa, $tahunAktif) {
                $q->whereHas('statistics', function($sq) use ($desa, $tahunAktif) {
                    $sq->where('desa_id', $desa->id)
                       ->where('year', $tahunAktif)
                       ->where('value', '>', 0);
                });
            }])->get();

        return view('desa.statistik', compact('desa', 'tahunAktif', 'statusPengisian'));
    }

    /**
     * Halaman Pengaturan Branding
     */
    public function edit()
    {
        $desa = auth()->user()->desa;
        abort_if(!$desa, 404);

        return view('desa.settings', compact('desa'));
    }

    /**
     * Proses Pembaruan File Logo dan Warna Tema Desa
     */
    public function updateBranding(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'header_color' => 'nullable|string',
            'accent_color' => 'nullable|string'
        ]);

        $desa = auth()->user()->desa;

        if ($request->hasFile('logo')) {
            if ($desa->logo && Storage::disk('public')->exists($desa->logo)) {
                Storage::disk('public')->delete($desa->logo);
            }

            $path = $request->file('logo')->store('logos', 'public');
            $desa->logo = $path;
        }

        if ($request->filled('header_color')) {
            $desa->header_color = $request->header_color;
        }

        if ($request->filled('accent_color')) {
            $desa->accent_color = $request->accent_color;
        }

        $desa->save();

        return back()->with('success', 'Branding Desa ' . $desa->nama_desa . ' berhasil diperbarui!');
    }

    /**
     * Monitoring Status Laporan Wilayah (Admin/Kabupaten)
     */
    public function statusLaporan(Request $request)
    {
        $desas = \App\Models\Desa::orderBy('kecamatan')->orderBy('nama_desa')->get();

        $listTahun = \App\Models\Statistic::select('year')
                        ->distinct()
                        ->orderBy('year', 'desc')
                        ->pluck('year');

        if($listTahun->isEmpty()) {
            $listTahun = collect([date('Y')]);
        }

        return view('admin.status_laporan', compact('desas', 'listTahun'));
    }
}
