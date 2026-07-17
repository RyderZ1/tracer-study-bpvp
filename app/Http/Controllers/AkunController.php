<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AkunController extends Controller
{
    /**
     * Tampilkan daftar semua akun.
     */
    public function index(Request $request)
    {
        $query = User::latest();

        // Handle optional search parameter (UX opsional yang diminta)
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('username_nik', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(10);
        
        // Mempertahankan query string pada pagination
        $users->appends($request->query());

        return view('admin.akun.index', compact('users'));
    }

    /**
     * Tampilkan form tambah akun baru.
     */
    public function create()
    {
        return view('admin.akun.create');
    }

    /**
     * Simpan akun baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username_nik'  => 'required|string|max:255|unique:users,username_nik',
            'nama_lengkap'  => 'required|string|max:255',
            'role'          => 'required|in:Admin,User',
            'status'        => 'required|in:Aktif,Nonaktif',
            'password'      => 'required|string|min:6|confirmed',
        ]);

        $data = $request->only(['username_nik', 'nama_lengkap', 'role', 'status']);
        $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);

        User::create($data);

        return redirect()->route('admin.akun.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail akun (opsional).
     */
    public function show(User $akun)
    {
        return view('admin.akun.show', compact('akun'));
    }

    /**
     * Tampilkan form edit akun.
     */
    public function edit(User $akun)
    {
        return view('admin.akun.edit', compact('akun'));
    }

    /**
     * Update data akun di database.
     */
    public function update(Request $request, User $akun)
    {
        $request->validate([
            'username_nik'  => 'required|string|max:255|unique:users,username_nik,' . $akun->id,
            'nama_lengkap'  => 'required|string|max:255',
            'role'          => 'required|in:Admin,User',
            'status'        => 'required|in:Aktif,Nonaktif',
            'password'      => 'nullable|string|min:6|confirmed',
        ]);

        $data = $request->only(['username_nik', 'nama_lengkap', 'role', 'status']);

        // Jika password diisi, enkripsi dan update password. Jika kosong, abaikan.
        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $akun->update($data);

        return redirect()->route('admin.akun.index')->with('success', 'Akun berhasil diperbarui.');
    }

    /**
     * Hapus akun dari database.
     */
    public function destroy(User $akun)
    {
        $akun->delete();

        return redirect()->route('admin.akun.index')->with('success', 'Akun berhasil dihapus.');
    }

    /**
     * Sinkronisasi data alumni ke tabel users.
     * Hanya memasukkan alumni yang NIK-nya belum terdaftar sebagai username di tabel users.
     */
    public function syncAlumni()
    {
        // 1. Ambil semua username_nik yang sudah terdaftar di tabel users
        $existingUsernames = User::pluck('username_nik')->toArray();

        // 2. Ambil data alumni yang NIK-nya belum ada di array existingUsernames
        // Menggunakan model Alumni, pastikan namespace-nya sudah benar atau panggil dengan namespace lengkap
        $alumniToSync = \App\Models\Alumni::whereNotIn('nik', $existingUsernames)->get();

        $count = 0;
        $now = now();
        $insertData = [];

        // 3. Loop untuk mapping data
        foreach ($alumniToSync as $alumnus) {
            $insertData[] = [
                'username_nik' => $alumnus->nik,
                'nama_lengkap' => $alumnus->nama_lengkap,
                'role'         => 'User', // Harus sesuai enum/nilai di database
                'status'       => 'Aktif',
                'password'     => \Illuminate\Support\Facades\Hash::make($alumnus->nik),
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
            
            $count++;
        }

        // 4. Lakukan batch insert jika ada data baru
        if ($count > 0) {
            // Karena kita memakai bcrypt, proses batch insert ini tetap memakan waktu 
            // jika jumlah data sangat besar (ratusan/ribuan) karena proses hashing password, 
            // namun batch insert tetap lebih optimal daripada individual User::create()
            User::insert($insertData);
        }

        return redirect()->route('admin.akun.index')->with('success', "Berhasil mensinkronkan {$count} akun baru dari data alumni.");
    }

    /**
     * Reset password akun menjadi default (sama dengan username/NIK).
     */
    public function resetPasswordToDefault(User $akun)
    {
        // Update password menjadi hash dari username_nik dan reset status request
        $akun->update([
            'password' => \Illuminate\Support\Facades\Hash::make($akun->username_nik),
            'is_requesting_reset' => false
        ]);

        return redirect()->route('admin.akun.index')->with('success', "Password untuk pengguna {$akun->nama_lengkap} berhasil di-reset menjadi default.");
    }
}
