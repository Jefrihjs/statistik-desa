<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DomainTracker;

class DomainMonitorController extends Controller
{
    public function index()
    {
        $domains = DomainTracker::with('desa')
            ->orderBy('days_left', 'asc') 
            ->get();

        return view('admin.domain-monitor', compact('domains'));
    }

    public function sslMonitor()
    {
        // Mengambil data dengan urutan SSL yang paling kritis (sisa hari paling sedikit) di atas
        $domains = DomainTracker::with('desa')
            ->orderBy('days_left', 'asc')
            ->get();

        return view('admin.ssl-monitor', compact('domains'));
    }
}