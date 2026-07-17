<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model.
     *
     * @var string
     */
    protected $table = 'alumni';

    /**
     * Kolom yang boleh diisi secara mass assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nik',
        'nama_lengkap',
        'jenis_kelamin',
        'no_telepon',
        'alamat',
        'foto',
        'tahun_lulus',
        'program_pelatihan',
        'status_bekerja',
        'status_kuesioner',
    ];

    /**
     * Relasi: Alumni dimiliki oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Alumni memiliki banyak JawabanKuesioner.
     */
    public function jawabanKuesioner()
    {
        return $this->hasMany(\App\Models\JawabanKuesioner::class);
    }
}
