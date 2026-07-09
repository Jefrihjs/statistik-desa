public function sslMonitor()
{
    $trackers = DomainTracker::with('desa')
        ->orderBy('domain_name')
        ->get();

    $sslRows = $trackers->map(function ($tracker) {
        $ssl = $this->checkSslCertificate($tracker->domain_name);

        return [
            'desa' => $tracker->desa,
            'domain_name' => $tracker->domain_name,
            'ssl_status' => $ssl['status'],
            'issuer' => $ssl['issuer'],
            'valid_from' => $ssl['valid_from'],
            'valid_to' => $ssl['valid_to'],
            'days_left' => $ssl['days_left'],
            'message' => $ssl['message'],
        ];
    });

    return view('admin.domain.ssl-monitor', compact('sslRows'));
}

private function checkSslCertificate(?string $domain): array
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
        8,
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
            'message' => 'SSL tidak dapat dicek / HTTPS tidak aktif',
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

    $validFrom = \Carbon\Carbon::createFromTimestamp($cert['validFrom_time_t']);
    $validTo = \Carbon\Carbon::createFromTimestamp($cert['validTo_time_t']);
    $daysLeft = now()->diffInDays($validTo, false);

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