<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AlumniProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $alumni = $user->alumni;

        if (!$alumni) {
            return redirect()->route('alumni.dashboard')->with('error', 'Data profil alumni Anda belum terdaftar di sistem.');
        }

        return view('alumni.profil', compact('user', 'alumni'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $alumni = $user->alumni;

        if (!$alumni) {
            return redirect()->route('alumni.dashboard')->with('error', 'Data profil alumni Anda belum terdaftar.');
        }

        $request->validate([
            'nama_lengkap'  => 'required|string|max:255',
            'email'         => 'nullable|email|max:255',
            'no_telepon'    => 'nullable|string|max:20',
            'alamat'        => 'nullable|string',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password_lama' => 'required_with:password',
            'password'      => 'nullable|string|min:6|confirmed',
        ]);

        if ($request->filled('password')) {
            if (!Hash::check($request->password_lama, $user->password)) {
                return redirect()->back()->with('error', 'Password lama tidak sesuai');
            }
        }

        // Update User table
        $userData = [
            'nama_lengkap' => $request->nama_lengkap,
            'email'        => $request->email,
        ];

        if ($request->filled('password')) {
            $userData['password'] = $request->password;
        }

        $user->update($userData);

        // Update Alumni table
        $alumniData = [
            'no_telepon' => $request->no_telepon,
            'alamat'     => $request->alamat,
        ];

        // Logika Upload Foto
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($alumni->foto && Storage::disk('public')->exists($alumni->foto)) {
                Storage::disk('public')->delete($alumni->foto);
            }

            $file = $request->file('foto');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('profil', $fileName, 'public');
            
            $alumniData['foto'] = $path;
        }

        $alumni->update($alumniData);

        // Memaksa Laravel menarik ulang data terbaru pengguna ke dalam memori aplikasi
        $user->refresh();

        return redirect()->route('alumni.profil.index')->with('success', 'Profil berhasil diperbarui!');
    }
}
