<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PertanyaanKuesioner extends Model
{
    use HasFactory;

    protected $table = 'pertanyaan_kuesioner';

    protected $fillable = [
        'pertanyaan',
        'tipe_jawaban',
        'wajib',
        'urutan',
    ];

    public function opsiJawaban()
    {
        return $this->hasMany(OpsiJawaban::class, 'pertanyaan_id');
    }

    public function jawabanKuesioner()
    {
        return $this->hasMany(JawabanKuesioner::class, 'pertanyaan_id');
    }
}
