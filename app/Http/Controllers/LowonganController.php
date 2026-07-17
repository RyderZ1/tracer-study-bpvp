<?php

namespace App\Http\Controllers;

use App\Models\Lowongan;
use Illuminate\Http\Request;

class LowonganController extends Controller
{
    /**
     * Tampilkan daftar lowongan kerja.
     */
    public function index()
    {
        $lowongan = Lowongan::orderBy('batas_waktu', 'desc')->paginate(10);
        return view('admin.lowongan.index', compact('lowongan'));
    }

    /**
     * Tampilkan form tambah lowongan baru.
     */
    public function create()
    {
        return view('admin.lowongan.create');
    }

    /**
     * Simpan data lowongan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'posisi'      => 'required|string|max:255',
            'perusahaan'  => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
            'lokasi'      => 'nullable|string|max:255',
            'batas_waktu' => 'required|date',
            'status'      => 'required|in:Aktif,Tutup',
        ]);

        Lowongan::create($request->all());

        return redirect()->route('admin.lowongan.index')->with('success', 'Lowongan kerja berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail lowongan kerja.
     */
    public function show(Lowongan $lowongan)
    {
        return view('admin.lowongan.show', compact('lowongan'));
    }

    /**
     * Tampilkan form edit lowongan kerja.
     */
    public function edit(Lowongan $lowongan)
    {
        return view('admin.lowongan.edit', compact('lowongan'));
    }

    /**
     * Update data lowongan kerja.
     */
    public function update(Request $request, Lowongan $lowongan)
    {
        $request->validate([
            'posisi'      => 'required|string|max:255',
            'perusahaan'  => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
            'lokasi'      => 'nullable|string|max:255',
            'batas_waktu' => 'required|date',
            'status'      => 'required|in:Aktif,Tutup',
        ]);

        $lowongan->update($request->all());

        return redirect()->route('admin.lowongan.index')->with('success', 'Lowongan kerja berhasil diperbarui.');
    }

    /**
     * Hapus data lowongan kerja.
     */
    public function destroy(Lowongan $lowongan)
    {
        $lowongan->delete();

        return redirect()->route('admin.lowongan.index')->with('success', 'Lowongan kerja berhasil dihapus.');
    }
}
