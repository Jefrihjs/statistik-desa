<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DomainTracker;
use Carbon\Carbon;

class CheckDomainExpiry extends Command
{
    protected $signature = 'domain:check';
    protected $description = 'Cek masa berlaku domain desa.id se-Beltim melalui WHOIS';

    public function handle()
    {
        $domains = DomainTracker::all();

        if ($domains->isEmpty()) {
            $this->warn("Belum ada data domain di tabel domain_trackers.");
            return;
        }

        foreach ($domains as $d) {
            $this->info("Mengecek: {$d->domain_name}...");

            $output = shell_exec("whois " . escapeshellarg($d->domain_name));

            // 1. Ambil Tanggal Kadaluarsa (Registry Expiry Date)
            if (preg_match('/Registry Expiry Date: ([\d-]+)/', $output, $matches)) {
                $expiry = Carbon::parse($matches[1]);
            }

            // 2. Ambil Tanggal Dibuat (Creation Date) - SESUAI HASIL TERMINAL BAPAK
            $createdDate = null;
            if (preg_match('/Creation Date: ([\d-]+)/', $output, $matches)) {
                $createdDate = Carbon::parse($matches[1]);
            }

            // 3. Ambil Nama Server (Name Server)
            $nameservers = null;
            if (preg_match_all('/Name Server: (.+)/i', $output, $matches)) {
                // Kita bersihkan dan gabungkan jadi satu string
                $nameservers = implode(', ', array_map('trim', $matches[1]));
            }

            // 3. Ambil Name Servers (Tetap sama)
            $nameservers = null;
            if (preg_match_all('/Name Server: (.+)/', $output, $matches)) {
                $nameservers = implode(', ', array_map('trim', $matches[1]));
            }

            if ($expiry) {
                $daysLeft = (int) now()->diffInDays($expiry, false);
                
                // Tentukan Status
                $status = 'Sehat';
                if ($daysLeft <= 0) $status = 'Expired';
                elseif ($daysLeft <= 30) $status = 'Kritis';

                // 4. Update ke Database
                $d->update([
                    'expiry_date' => $expiry,
                    'created_date' => $createdDate,
                    'nameservers' => $nameservers,
                    'days_left' => $daysLeft,
                    'status' => $status,
                    'last_checked_at' => now(),
                ]);

                $this->info("Hasil: {$status} ({$daysLeft} hari lagi)");
            } else {
                $this->error("Gagal mendapatkan data expiry untuk {$d->domain_name}");
            }
            
            // Jeda agar tidak diblokir
            sleep(2);
        }

        $this->info("Pengecekan selesai!");
    }
}