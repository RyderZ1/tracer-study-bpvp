<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tahun_lulus = $request->input('tahun_lulus');
        $program_pelatihan = $request->input('program_pelatihan');

        // Query Alumni dengan filter
        $query = Alumni::query()
            ->when($tahun_lulus, function ($q, $tahun) {
                return $q->where('tahun_lulus', $tahun);
            })
            ->when($program_pelatihan, function ($q, $program) {
                return $q->where('program_pelatihan', $program);
            });

        $total_alumni = (clone $query)->count();
        $jumlah_bekerja = (clone $query)->where('status_bekerja', 'Bekerja')->count();
        $jumlah_belum = (clone $query)->where('status_bekerja', 'Belum Bekerja')->count();
        $jumlah_wirausaha = (clone $query)->where('status_bekerja', 'Wirausaha')->count();

        // Hitung persentase
        $persentase_bekerja = $total_alumni > 0 ? round(($jumlah_bekerja / $total_alumni) * 100, 2) : 0;
        $persentase_belum = $total_alumni > 0 ? round(($jumlah_belum / $total_alumni) * 100, 2) : 0;
        $persentase_wirausaha = $total_alumni > 0 ? round(($jumlah_wirausaha / $total_alumni) * 100, 2) : 0;

        // Ambil data unik untuk dropdown filter
        $list_tahun = Alumni::select('tahun_lulus')->distinct()->orderBy('tahun_lulus', 'desc')->pluck('tahun_lulus');
        $list_program = Alumni::select('program_pelatihan')->distinct()->orderBy('program_pelatihan', 'asc')->pluck('program_pelatihan');

        return view('admin.laporan.index', compact(
            'total_alumni',
            'jumlah_bekerja',
            'jumlah_belum',
            'jumlah_wirausaha',
            'persentase_bekerja',
            'persentase_belum',
            'persentase_wirausaha',
            'list_tahun',
            'list_program',
            'tahun_lulus',
            'program_pelatihan'
        ));
    }
    public function cetak(Request $request)
    {
        $tahun_lulus = $request->input('tahun_lulus');
        $program_pelatihan = $request->input('program_pelatihan');

        // Query Alumni dengan filter
        $query = Alumni::query()
            ->when($tahun_lulus, function ($q, $tahun) {
                return $q->where('tahun_lulus', $tahun);
            })
            ->when($program_pelatihan, function ($q, $program) {
                return $q->where('program_pelatihan', $program);
            });

        // Rekapitulasi Total
        $total_alumni = (clone $query)->count();
        $jumlah_bekerja = (clone $query)->where('status_bekerja', 'Bekerja')->count();
        $jumlah_belum = (clone $query)->where('status_bekerja', 'Belum Bekerja')->count();
        $jumlah_wirausaha = (clone $query)->where('status_bekerja', 'Wirausaha')->count();

        // Hitung persentase
        $persentase_bekerja = $total_alumni > 0 ? round(($jumlah_bekerja / $total_alumni) * 100, 2) : 0;
        $persentase_belum = $total_alumni > 0 ? round(($jumlah_belum / $total_alumni) * 100, 2) : 0;
        $persentase_wirausaha = $total_alumni > 0 ? round(($jumlah_wirausaha / $total_alumni) * 100, 2) : 0;

        // Ambil list data alumni
        $alumni = $query->get();

        return view('admin.laporan.cetak', compact(
            'total_alumni',
            'jumlah_bekerja',
            'jumlah_belum',
            'jumlah_wirausaha',
            'persentase_bekerja',
            'persentase_belum',
            'persentase_wirausaha',
            'alumni',
            'tahun_lulus',
            'program_pelatihan'
        ));
    }

    public function download(Request $request)
    {
        $tahun_lulus = $request->input('tahun_lulus');
        $program_pelatihan = $request->input('program_pelatihan');

        // Query Alumni dengan filter
        $query = Alumni::query()
            ->when($tahun_lulus, function ($q, $tahun) {
                return $q->where('tahun_lulus', $tahun);
            })
            ->when($program_pelatihan, function ($q, $program) {
                return $q->where('program_pelatihan', $program);
            });

        $alumni = $query->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=\"Laporan_Tracer_Study.csv\"",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($alumni) {
            $file = fopen('php://output', 'w');
            
            // Tambahkan BOM untuk Excel agar karakter spesial terbaca dengan baik (opsional, tapi disarankan)
            fputs($file, "\xEF\xBB\xBF");

            // Kolom header
            fputcsv($file, ['No', 'NIK', 'Nama Lengkap', 'Tahun Lulus', 'Program Pelatihan', 'Status Bekerja']);

            // Data rows
            $no = 1;
            foreach ($alumni as $row) {
                fputcsv($file, [
                    $no++,
                    $row->nik,
                    $row->nama_lengkap,
                    $row->tahun_lulus,
                    $row->program_pelatihan,
                    $row->status_bekerja
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
