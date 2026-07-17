<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanKuesioner extends Model
{
    use HasFactory;

    protected $table = 'jawaban_kuesioner';

    protected $fillable = [
        'alumni_id',
        'pertanyaan_id',
        'jawaban',
    ];

    public function alumni()
    {
        return $this->belongsTo(Alumni::class, 'alumni_id');
    }

    public function pertanyaan()
    {
        return $this->belongsTo(PertanyaanKuesioner::class, 'pertanyaan_id');
    }
}
