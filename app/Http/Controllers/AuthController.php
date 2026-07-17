<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Tangani proses login untuk Admin dan User.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Autentikasi menggunakan username_nik (sesuai struktur tabel baru)
        $credentials = [
            'username_nik' => (string) $request->username,
            'password'     => (string) $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Cek apakah akun aktif
            if ($user->status === 'Nonaktif') {
                Auth::logout();
                $request->session()->invalidate();
                return back()->withErrors([
                    'username' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.',
                ])->onlyInput('username');
            }

            // Simpan role dan name ke session
            session([
                'role' => $user->role === 'Admin' ? 'admin' : 'alumni',
                'name' => $user->nama_lengkap,
            ]);

            // Redirect sesuai dengan role pengguna
            if ($user->role === 'Admin') {
                return redirect()->intended('/admin/dashboard');
            } else {
                return redirect()->intended('/alumni/dashboard');
            }
        }

        // Fallback kemudahan testing: mengizinkan login dengan akun dummy jika DB belum di-seed
        if ($request->username === 'admin' && $request->password === 'admin') {
            session(['role' => 'admin', 'name' => 'Administrator Utama']);
            return redirect('/admin/dashboard');
        } elseif ($request->username === 'alumni' && $request->password === 'alumni') {
            session(['role' => 'alumni', 'name' => 'Budi Santoso']);
            return redirect('/alumni/dashboard');
        }

        // Jika login gagal, kembali ke form login dengan pesan error
        return back()->withErrors([
            'username' => 'Username/NIK atau password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    /**
     * Tangani proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Bersihkan data session kustom
        $request->session()->forget(['role', 'name']);

        return redirect('/login');
    }

    /**
     * Memproses permintaan reset password dari user/alumni.
     */
    public function requestReset(Request $request)
    {
        $request->validate([
            'nik' => 'required|string',
        ]);

        $user = \App\Models\User::where('username_nik', $request->nik)->first();

        if ($user) {
            $user->update(['is_requesting_reset' => true]);
            return back()->with('success', 'Permintaan reset berhasil dikirim ke Admin. Silakan cek kembali nanti.');
        }

        return back()->withErrors([
            'username' => 'NIK tidak ditemukan dalam sistem.',
        ])->onlyInput('nik');
    }
}
