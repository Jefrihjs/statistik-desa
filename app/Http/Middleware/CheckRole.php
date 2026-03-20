<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!auth()->check() || auth()->user()->role !== $role) {
            // Jika bukan admin, lempar ke dashboard desa atau login
            return auth()->user()->role === 'desa' 
                ? redirect()->route('desa.dashboard')->with('error', 'Akses Dibatasi!')
                : redirect('/login');
        }

        return $next($request);
    }
}