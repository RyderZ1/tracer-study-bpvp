<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckKuesionerFilled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek jika menggunakan Autentikasi database riil
        if (Auth::check()) {
            $user = Auth::user();
            if (!$user->is_kuesioner_filled) {
                return redirect('/alumni/kuesioner')->with('warning', 'Akses diblokir: Anda harus mengisi kuesioner terlebih dahulu untuk dapat melihat Lowongan Kerja.');
            }
        } 
        // 2. Cek fallback session untuk akun dummy testing
        elseif (session('role') === 'alumni') {
            if (!session('is_kuesioner_filled', false)) {
                return redirect('/alumni/kuesioner')->with('warning', 'Akses diblokir: Anda harus mengisi kuesioner terlebih dahulu untuk dapat melihat Lowongan Kerja.');
            }
        }

        return $next($request);
    }
}
