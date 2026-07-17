<?php

namespace App\Http\Controllers;

use App\Models\Lowongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlumniLowonganController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $alumni = $user->alumni;

        if (!$alumni) {
            return redirect()->route('alumni.dashboard')->with('error', 'Data profil alumni Anda belum terdaftar.');
        }

        // Logika Gatekeeper (Validasi ketat)
        if ($alumni->status_kuesioner === 'Belum') {
            return redirect()->route('alumni.kuesioner.index')
                ->with('warning', 'Akses diblokir: Anda harus mengisi kuesioner terlebih dahulu untuk dapat melihat Lowongan Kerja.');
        }

        // Ambil semua data lowongan aktif
        $lowongans = Lowongan::where('status', 'Aktif')
            ->orderBy('batas_waktu', 'desc')
            ->get();

        return view('alumni.lowongan', compact('lowongans'));
    }
}
