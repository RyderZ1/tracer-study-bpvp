<?php

namespace App\Http\Controllers;

use App\Models\Lowongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlumniDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user) {
            // Cek kondisi apakah relasi profil alumninya ada
            if (!$user->alumni) {
                // Cari berdasarkan NIK terlebih dahulu untuk menghindari duplikasi
                $alumni = \App\Models\Alumni::firstOrNew(['nik' => $user->username_nik]);
                $alumni->user_id = $user->id;
                
                // Jika data baru (belum ada di database), isi nilai default
                if (!$alumni->exists) {
                    $alumni->nama_lengkap = $user->nama_lengkap;
                    $alumni->status_kuesioner = 'Belum';
                    $alumni->tahun_lulus = date('Y');
                    $alumni->program_pelatihan = 'Belum Diisi';
                    $alumni->jenis_kelamin = 'L';
                    $alumni->status_bekerja = 'Belum Bekerja';
                }
                
                $alumni->save();
                
                // Muat ulang relasi tabel alumni yang baru saja terbuat/diupdate
                $user->refresh();
            }
            
            $status_kuesioner = $user->alumni->status_kuesioner;
        } else {
            // Fallback jika tidak ada user yang terautentikasi (seharusnya tercover middleware)
            $status_kuesioner = 'Belum';
        }

        // Hitung lowongan baru yang aktif
        $lowongan_baru = Lowongan::where('status', 'Aktif')->count();

        // Kembalikan (return) data tersebut ke view('alumni.dashboard')
        return view('alumni.dashboard', compact('status_kuesioner', 'lowongan_baru'));
    }
}
