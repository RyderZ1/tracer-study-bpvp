<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekAdmin
{
    /**
     * Middleware untuk memastikan hanya Admin yang dapat mengakses.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek autentikasi via Auth guard
        if (Auth::check()) {
            if (Auth::user()->role === 'Admin') {
                return $next($request);
            }
            return redirect('/login')->with('error', 'Anda tidak memiliki akses sebagai Admin.');
        }

        // Fallback: cek session untuk akun dummy testing
        if (session('role') === 'admin' || session('role') === 'Admin') {
            return $next($request);
        }

        return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
    }
}
