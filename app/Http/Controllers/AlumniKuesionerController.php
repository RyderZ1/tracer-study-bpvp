<?php

namespace App\Http\Controllers;

use App\Models\JawabanKuesioner;
use App\Models\PertanyaanKuesioner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AlumniKuesionerController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $alumni = $user->alumni;

        if (!$alumni) {
            return redirect()->route('alumni.dashboard')->with('error', 'Data profil alumni Anda belum terdaftar.');
        }

        if ($alumni->status_kuesioner === 'Sudah') {
            return redirect()->route('alumni.dashboard')->with('success', 'Anda sudah mengisi kuesioner.');
        }

        $pertanyaans = PertanyaanKuesioner::with('opsiJawaban')->orderBy('urutan', 'asc')->get();

        return view('alumni.kuesioner', compact('pertanyaans'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $alumni = $user->alumni;

        if (!$alumni) {
            return redirect()->route('alumni.dashboard')->with('error', 'Data profil alumni Anda belum terdaftar.');
        }

        $request->validate([
            'jawaban' => 'required|array',
        ]);

        DB::transaction(function () use ($request, $alumni) {
            foreach ($request->jawaban as $pertanyaan_id => $jawaban) {
                JawabanKuesioner::create([
                    'alumni_id'     => $alumni->id,
                    'pertanyaan_id' => $pertanyaan_id,
                    'jawaban'       => $jawaban,
                ]);
            }

            $alumni->update([
                'status_kuesioner' => 'Sudah'
            ]);
        });

        return redirect()->route('alumni.dashboard')->with('success', 'Kuesioner berhasil disimpan! Terima kasih atas partisipasi Anda.');
    }
}
