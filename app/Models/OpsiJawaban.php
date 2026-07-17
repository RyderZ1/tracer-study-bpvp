<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpsiJawaban extends Model
{
    use HasFactory;

    protected $table = 'opsi_jawaban';

    protected $fillable = [
        'pertanyaan_id',
        'opsi',
    ];

    public function pertanyaan()
    {
        return $this->belongsTo(PertanyaanKuesioner::class, 'pertanyaan_id');
    }
}
