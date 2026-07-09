<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    public static function log(
        string $module,
        string $action,
        ?string $description = null,
        array $properties = []
    ): void {
        $user = Auth::user();

        ActivityLog::create([
            'user_id' => $user?->id,
            'desa_id' => $user?->desa_id,
            'role' => $user?->role,
            'module' => $module,
            'action' => $action,
            'description' => $description,
            'route_name' => Request::route()?->getName(),
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'properties' => $properties,
        ]);
    }
}