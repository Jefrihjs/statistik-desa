<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Desa;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = ActivityLog::with(['user', 'desa'])
            ->when($request->desa_id, function ($query) use ($request) {
                $query->where('desa_id', $request->desa_id);
            })
            ->when($request->module, function ($query) use ($request) {
                $query->where('module', $request->module);
            })
            ->when($request->keyword, function ($query) use ($request) {
                $keyword = $request->keyword;

                $query->where(function ($q) use ($keyword) {
                    $q->where('action', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%")
                        ->orWhereHas('user', function ($userQuery) use ($keyword) {
                            $userQuery->where('name', 'like', "%{$keyword}%");
                        })
                        ->orWhereHas('desa', function ($desaQuery) use ($keyword) {
                            $desaQuery->where('nama_desa', 'like', "%{$keyword}%");
                        });
                });
            })
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $desas = Desa::orderBy('nama_desa')->get();

        $modules = ActivityLog::query()
            ->select('module')
            ->whereNotNull('module')
            ->distinct()
            ->orderBy('module')
            ->pluck('module');

        return view('admin.activity-log.index', compact('logs', 'desas', 'modules'));
    }
}