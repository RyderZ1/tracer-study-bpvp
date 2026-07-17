<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pertanyaan_kuesioner', function (Blueprint $table) {
            $table->id();
            $table->text('pertanyaan');
            $table->enum('tipe_jawaban', ['Pilihan Ganda', 'Teks Singkat']);
            $table->boolean('wajib')->default(true);
            $table->integer('urutan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pertanyaan_kuesioner');
    }
};
