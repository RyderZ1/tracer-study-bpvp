<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek autentikasi via Auth guard
        if (Auth::check()) {
            if (Auth::user()->role === 'User') {
                return $next($request);
            }
            return redirect('/login')->with('error', 'Anda tidak memiliki akses sebagai User (Alumni).');
        }

        // Fallback: cek session untuk akun dummy testing
        if (session('role') === 'alumni' || session('role') === 'User') {
            return $next($request);
        }

        return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
    }
}
