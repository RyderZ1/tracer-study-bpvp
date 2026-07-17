<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Lowongan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin.
     */
    public function index()
    {
        // 1. Widget Statistik
        $total_alumni = Alumni::count();
        $kuesioner_terisi = Alumni::where('status_kuesioner', 'Sudah')->count();
        $lowongan_aktif = Lowongan::where('status', 'Aktif')->count();
        
        // Handle pembagian dengan nol
        $persentase_kuesioner = $total_alumni > 0 ? round(($kuesioner_terisi / $total_alumni) * 100, 2) : 0;

        // 2. Tabel Aktivitas (5 data terbaru yang sudah mengisi kuesioner)
        $aktivitas_terbaru = Alumni::where('status_kuesioner', 'Sudah')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get(['nama_lengkap', 'program_pelatihan', 'updated_at', 'status_bekerja']);

        // 3. Data Chart
        $data_status_bekerja = Alumni::selectRaw('status_bekerja, COUNT(*) as count')
            ->groupBy('status_bekerja')
            ->get();

        $data_per_tahun = Alumni::selectRaw('tahun_lulus, COUNT(*) as count')
            ->groupBy('tahun_lulus')
            ->orderBy('tahun_lulus', 'asc')
            ->get();

        return view('admin.dashboard', compact(
            'total_alumni',
            'kuesioner_terisi',
            'lowongan_aktif',
            'persentase_kuesioner',
            'aktivitas_terbaru',
            'data_status_bekerja',
            'data_per_tahun'
        ));
    }
}
