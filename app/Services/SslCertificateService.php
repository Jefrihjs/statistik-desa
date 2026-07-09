<?php

namespace App\Services;

use Carbon\Carbon;

class SslCertificateService
{
    public function check(?string $domain): array
    {
        if (!$domain) {
            return [
                'status' => 'unknown',
                'issuer' => '-',
                'valid_from' => null,
                'valid_to' => null,
                'days_left' => null,
                'message' => 'Domain belum tersedia',
            ];
        }

        $host = preg_replace('#^https?://#', '', $domain);
        $host = trim($host, '/');

        $context = stream_context_create([
            'ssl' => [
                'capture_peer_cert' => true,
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);

        $client = @stream_socket_client(
            'ssl://' . $host . ':443',
            $errno,
            $errstr,
            6,
            STREAM_CLIENT_CONNECT,
            $context
        );

        if (!$client) {
            return [
                'status' => 'inactive',
                'issuer' => '-',
                'valid_from' => null,
                'valid_to' => null,
                'days_left' => null,
                'message' => 'SSL tidak aktif / tidak dapat dicek',
            ];
        }

        $params = stream_context_get_params($client);

        if (!isset($params['options']['ssl']['peer_certificate'])) {
            return [
                'status' => 'inactive',
                'issuer' => '-',
                'valid_from' => null,
                'valid_to' => null,
                'days_left' => null,
                'message' => 'Sertifikat SSL tidak ditemukan',
            ];
        }

        $cert = openssl_x509_parse($params['options']['ssl']['peer_certificate']);

        if (!$cert || !isset($cert['validTo_time_t'])) {
            return [
                'status' => 'unknown',
                'issuer' => '-',
                'valid_from' => null,
                'valid_to' => null,
                'days_left' => null,
                'message' => 'Sertifikat tidak valid',
            ];
        }

        $validFrom = Carbon::createFromTimestamp($cert['validFrom_time_t']);
        $validTo = Carbon::createFromTimestamp($cert['validTo_time_t']);
        $daysLeft = (int) now()->startOfDay()->diffInDays($validTo->copy()->startOfDay(), false);

        $issuer = $cert['issuer']['O']
            ?? $cert['issuer']['CN']
            ?? '-';

        return [
            'status' => $daysLeft >= 0 ? 'active' : 'expired',
            'issuer' => $issuer,
            'valid_from' => $validFrom,
            'valid_to' => $validTo,
            'days_left' => $daysLeft,
            'message' => $daysLeft >= 0
                ? $daysLeft . ' hari lagi'
                : 'SSL sudah kedaluwarsa',
        ];
    }
}