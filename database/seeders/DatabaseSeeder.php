<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Alumni;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin
        User::create([
            'username_nik'  => 'admin',
            'nama_lengkap'  => 'Administrator Utama',
            'role'          => 'Admin',
            'status'        => 'Aktif',
            'password'      => 'password',
        ]);

        // 2. Akun Alumni (User) + record di tabel alumni
        $userAlumni = User::create([
            'username_nik'  => '3515012345678901',
            'nama_lengkap'  => 'Budi Santoso',
            'role'          => 'User',
            'status'        => 'Aktif',
            'password'      => 'password',
        ]);

        Alumni::create([
            'user_id'           => $userAlumni->id,
            'nik'               => '3515012345678901',
            'nama_lengkap'      => 'Budi Santoso',
            'jenis_kelamin'     => 'L',
            'no_telepon'        => '081234567890',
            'tahun_lulus'       => '2025',
            'program_pelatihan' => 'Teknik Komputer',
            'status_bekerja'    => 'Belum Bekerja',
            'status_kuesioner'  => 'Belum',
        ]);
    }
}
