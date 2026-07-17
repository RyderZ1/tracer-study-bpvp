<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\User;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    /**
     * Tampilkan daftar semua data alumni.
     */
    public function index()
    {
        $alumni = Alumni::with('user')->latest()->paginate(10);
        return view('admin.alumni.index', compact('alumni'));
    }

    /**
     * Tampilkan form tambah data alumni baru.
     */
    public function create()
    {
        // Ambil user dengan role 'User' yang belum terhubung ke alumni
        $users = User::where('role', 'User')
            ->whereDoesntHave('alumni')
            ->get();

        return view('admin.alumni.create', compact('users'));
    }

    /**
     * Simpan data alumni baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'            => 'nullable|exists:users,id',
            'nik'                => 'required|string|max:20|unique:alumni,nik',
            'nama_lengkap'       => 'required|string|max:255',
            'jenis_kelamin'      => 'required|in:L,P',
            'no_telepon'         => 'nullable|string|max:20',
            'tahun_lulus'        => 'required|string|max:4',
            'program_pelatihan'  => 'required|string|max:255',
            'status_bekerja'     => 'required|in:Bekerja,Belum Bekerja,Wirausaha',
            'status_kuesioner'   => 'required|in:Belum,Sudah',
        ]);

        Alumni::create($request->only([
            'user_id', 'nik', 'nama_lengkap', 'jenis_kelamin',
            'no_telepon', 'tahun_lulus', 'program_pelatihan',
            'status_bekerja', 'status_kuesioner',
        ]));

        return redirect()->route('admin.alumni.index')->with('success', 'Data alumni berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail data alumni.
     */
    public function show(Alumni $alumnus)
    {
        $alumnus->load('user');
        return view('admin.alumni.show', compact('alumnus'));
    }

    /**
     * Tampilkan form edit data alumni.
     */
    public function edit(Alumni $alumnus)
    {
        // Ambil user dengan role 'User' yang belum terhubung ke alumni, atau user yang saat ini terhubung
        $users = User::where('role', 'User')
            ->where(function ($query) use ($alumnus) {
                $query->whereDoesntHave('alumni')
                      ->orWhere('id', $alumnus->user_id);
            })
            ->get();

        return view('admin.alumni.edit', compact('alumnus', 'users'));
    }

    /**
     * Update data alumni di database.
     */
    public function update(Request $request, Alumni $alumnus)
    {
        $request->validate([
            'user_id'            => 'nullable|exists:users,id',
            'nik'                => 'required|string|max:20|unique:alumni,nik,' . $alumnus->id,
            'nama_lengkap'       => 'required|string|max:255',
            'jenis_kelamin'      => 'required|in:L,P',
            'no_telepon'         => 'nullable|string|max:20',
            'tahun_lulus'        => 'required|string|max:4',
            'program_pelatihan'  => 'required|string|max:255',
            'status_bekerja'     => 'required|in:Bekerja,Belum Bekerja,Wirausaha',
            'status_kuesioner'   => 'required|in:Belum,Sudah',
        ]);

        $alumnus->update($request->only([
            'user_id', 'nik', 'nama_lengkap', 'jenis_kelamin',
            'no_telepon', 'tahun_lulus', 'program_pelatihan',
            'status_bekerja', 'status_kuesioner',
        ]));

        return redirect()->route('admin.alumni.index')->with('success', 'Data alumni berhasil diperbarui.');
    }

    /**
     * Hapus data alumni dari database.
     */
    public function destroy(Alumni $alumnus)
    {
        $alumnus->delete();

        return redirect()->route('admin.alumni.index')->with('success', 'Data alumni berhasil dihapus.');
    }

    public function import(Request $request)
    {
        // Validasi file upload (hanya menerima CSV)
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        
        $handle = fopen($file->path(), 'r');
        if ($handle === false) {
            return redirect()->back()->with('error', 'Gagal membuka file.');
        }

        // Membaca header (baris pertama)
        $header = fgetcsv($handle, 1000, ',');

        $successCount = 0;

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                // Memastikan jumlah kolom minimal 5 sesuai header (NIK, NAMA, L/P, TAHUN, PROGRAM)
                if (count($row) < 5) continue;

                $nik = trim($row[0]);
                $nama = trim($row[1]);
                $lp = trim($row[2]);
                $tahun = trim($row[3]);
                $program = trim($row[4]);

                // Lewati jika NIK atau Nama kosong
                if (empty($nik) || empty($nama)) continue;

                // Insert atau update data alumni
                Alumni::updateOrCreate(
                    ['nik' => $nik],
                    [
                        'nama_lengkap' => $nama,
                        'jenis_kelamin' => strtoupper($lp) == 'L' ? 'L' : 'P',
                        'tahun_lulus' => $tahun,
                        'program_pelatihan' => $program,
                        'status_bekerja' => 'Belum Bekerja',
                        'status_kuesioner' => 'Belum',
                    ]
                );
                $successCount++;
            }
            \Illuminate\Support\Facades\DB::commit();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat import data: ' . $e->getMessage());
        } finally {
            fclose($handle);
        }

        return redirect()->route('admin.alumni.index')->with('success', "Import berhasil. {$successCount} data alumni telah ditambahkan/diperbarui.");
    }

    public function exportTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_import_alumni.csv"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Header kolom sesuai permintaan
            fputcsv($file, ['NIK', 'NAMA LENGKAP', 'L/P', 'TAHUN LULUS', 'PROGRAM PELATIHAN']);
            
            fclose($file);
        };

        return \Illuminate\Support\Facades\Response::stream($callback, 200, $headers);
    }
}
