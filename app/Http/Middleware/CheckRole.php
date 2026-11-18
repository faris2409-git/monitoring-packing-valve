<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('warning', 'Sesi Anda telah berakhir. Silakan login kembali.');
        }

        if (auth()->user()->role !== $role) {
            if (auth()->user()->role === 'supervisor') {
                return redirect()->route('supervisor.dashboard')->with('info', 'Anda dialihkan ke Dashboard Supervisor.');
            } elseif (auth()->user()->role === 'operator') {
                return redirect()->route('operator.dashboard')->with('info', 'Anda dialihkan ke Dashboard Operator.');
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
}