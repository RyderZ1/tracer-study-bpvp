<?php

namespace App\Http\Controllers;

use App\Models\PertanyaanKuesioner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KuesionerController extends Controller
{
    public function index()
    {
        $pertanyaans = PertanyaanKuesioner::with('opsiJawaban')->orderBy('urutan', 'asc')->paginate(10);
        return view('admin.kuesioner.index', compact('pertanyaans'));
    }

    public function create()
    {
        return view('admin.kuesioner.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pertanyaan'   => 'required|string',
            'tipe_jawaban' => 'required|in:Pilihan Ganda,Teks Singkat',
            'wajib'        => 'boolean',
            'urutan'       => 'required|integer',
            'opsi'         => 'nullable|array',
            'opsi.*'       => 'required_if:tipe_jawaban,Pilihan Ganda|string',
        ]);

        DB::transaction(function () use ($request) {
            $pertanyaan = PertanyaanKuesioner::create([
                'pertanyaan'   => $request->pertanyaan,
                'tipe_jawaban' => $request->tipe_jawaban,
                'wajib'        => $request->has('wajib'),
                'urutan'       => $request->urutan,
            ]);

            if ($request->tipe_jawaban === 'Pilihan Ganda' && $request->has('opsi')) {
                $opsiData = array_map(function ($opsiValue) {
                    return ['opsi' => $opsiValue];
                }, $request->opsi);
                
                $pertanyaan->opsiJawaban()->createMany($opsiData);
            }
        });

        return redirect()->route('admin.kuesioner.index')->with('success', 'Pertanyaan berhasil ditambahkan.');
    }

    public function show(PertanyaanKuesioner $kuesioner)
    {
        // $kuesioner is bound to PertanyaanKuesioner model
        $kuesioner->load('opsiJawaban');
        return view('admin.kuesioner.show', compact('kuesioner'));
    }

    public function edit(PertanyaanKuesioner $kuesioner)
    {
        $kuesioner->load('opsiJawaban');
        return view('admin.kuesioner.edit', compact('kuesioner'));
    }

    public function update(Request $request, PertanyaanKuesioner $kuesioner)
    {
        $request->validate([
            'pertanyaan'   => 'required|string',
            'tipe_jawaban' => 'required|in:Pilihan Ganda,Teks Singkat',
            'wajib'        => 'boolean',
            'urutan'       => 'required|integer',
            'opsi'         => 'nullable|array',
            'opsi.*'       => 'required_if:tipe_jawaban,Pilihan Ganda|string',
        ]);

        DB::transaction(function () use ($request, $kuesioner) {
            $kuesioner->update([
                'pertanyaan'   => $request->pertanyaan,
                'tipe_jawaban' => $request->tipe_jawaban,
                'wajib'        => $request->has('wajib'),
                'urutan'       => $request->urutan,
            ]);

            // Hapus opsi lama dan buat yang baru jika tipe pilihan ganda
            $kuesioner->opsiJawaban()->delete();
            
            if ($request->tipe_jawaban === 'Pilihan Ganda' && $request->has('opsi')) {
                $opsiData = array_map(function ($opsiValue) {
                    return ['opsi' => $opsiValue];
                }, $request->opsi);
                
                $kuesioner->opsiJawaban()->createMany($opsiData);
            }
        });

        return redirect()->route('admin.kuesioner.index')->with('success', 'Pertanyaan berhasil diperbarui.');
    }

    public function destroy(PertanyaanKuesioner $kuesioner)
    {
        $kuesioner->delete();
        return redirect()->route('admin.kuesioner.index')->with('success', 'Pertanyaan berhasil dihapus.');
    }
}
