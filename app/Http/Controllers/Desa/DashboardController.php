<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Halaman Hub Utama TARSIUS (Menampilkan 5 Card Layanan)
     */
    public function index()
    {
        $desa = auth()->user()->desa; 
        abort_if(!$desa, 404, 'Akun Anda belum terhubung dengan data desa mana pun.');

        // 1. Ambil data tracking domain dari database
        $tracker = DB::table('domain_trackers')
            ->where('desa_id', $desa->id)
            ->first();

        // 2. Lakukan live check SSL berdasarkan domain_name dari database
        $sslInfo = [
            'status' => 'error',
            'pesan' => 'Belum Terdata',
            'hari' => 0
        ];

        if ($tracker && $tracker->domain_name) {
            $sslInfo = $this->getRealSslDays($tracker->domain_name);
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

            // Koneksi via port 443 dengan timeout 3 detik
            $client = @stream_socket_client(
                "ssl://" . $cleanDomain . ":443",
                $errno,
                $errstr,
                3,
                STREAM_CLIENT_CONNECT,
                $streamContext
            );

            if (!$client) {
                return ['status' => 'error', 'pesan' => 'Tidak Terkoneksi', 'hari' => 0];
            }

            $params = stream_context_get_params($client);
            $cert = openssl_x509_parse($params["options"]["ssl"]["peer_certificate"]);
            
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

        } catch (\Exception $e) {
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