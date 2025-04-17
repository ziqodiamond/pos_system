<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GudangMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user terotentikasi dan memiliki role 'super_admin'
        if (Auth::check() && Auth::user()->role == 'gudang') {
            return $next($request); // Lanjutkan ke route yang diminta
        }

        // Jika tidak, redirect ke halaman lain
        abort(403, 'Akses ditolak. Hanya untuk Gudang.');
    }
}
