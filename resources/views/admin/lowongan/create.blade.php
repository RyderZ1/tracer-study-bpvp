@extends('layouts.app')

@section('title', 'Tambah Lowongan - Tracer Study')
@section('page_title', 'Tambah Lowongan Kerja')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">Form Tambah Lowongan</div>
        <a href="{{ route('admin.lowongan.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">

        @if($errors->any())
        <div style="background: #f8d7da; color: #721c24; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #f5c6cb;">
            <ul style="margin: 0; padding-left: 1.25rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.lowongan.store') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label for="posisi" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--dark);">
                        Posisi Pekerjaan <span style="color: red;">*</span>
                    </label>
                    <input type="text" id="posisi" name="posisi" class="form-input" 
                        value="{{ old('posisi') }}" placeholder="Contoh: Web Developer" required>
                </div>
                <div>
                    <label for="perusahaan" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--dark);">
                        Nama Perusahaan <span style="color: red;">*</span>
                    </label>
                    <input type="text" id="perusahaan" name="perusahaan" class="form-input" 
                        value="{{ old('perusahaan') }}" placeholder="Contoh: PT Teknologi Bangsa" required>
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="lokasi" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--dark);">
                    Lokasi
                </label>
                <input type="text" id="lokasi" name="lokasi" class="form-input" 
                    value="{{ old('lokasi') }}" placeholder="Contoh: Jakarta Selatan">
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="deskripsi" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--dark);">
                    Deskripsi Pekerjaan
                </label>
                <textarea id="deskripsi" name="deskripsi" class="form-input" rows="5" 
                    placeholder="Tuliskan detail pekerjaan, kualifikasi, dsb.">{{ old('deskripsi') }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <label for="batas_waktu" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--dark);">
                        Batas Waktu Lamaran <span style="color: red;">*</span>
                    </label>
                    <input type="date" id="batas_waktu" name="batas_waktu" class="form-input" 
                        value="{{ old('batas_waktu') }}" required>
                </div>
                <div>
                    <label for="status" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--dark);">
                        Status <span style="color: red;">*</span>
                    </label>
                    <select id="status" name="status" class="form-input" required>
                        <option value="Aktif" {{ old('status', 'Aktif') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Tutup" {{ old('status') === 'Tutup' ? 'selected' : '' }}>Tutup</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 0.75rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Lowongan
                </button>
                <a href="{{ route('admin.lowongan.index') }}" class="btn btn-outline">
                    Batal
                </a>
            </div>
        </form>

    </div>
</div>
@endsection
