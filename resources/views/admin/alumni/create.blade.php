@extends('layouts.app')

@section('title', 'Tambah Alumni - Tracer Study')
@section('page_title', 'Tambah Data Alumni')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">Form Tambah Data Alumni</div>
        <a href="{{ route('admin.alumni.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">

        {{-- Tampilkan error validasi --}}
        @if($errors->any())
        <div style="background: #f8d7da; color: #721c24; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #f5c6cb;">
            <ul style="margin: 0; padding-left: 1.25rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.alumni.store') }}" method="POST">
            @csrf

            {{-- Hubungkan ke Akun User (opsional) --}}
            <div style="margin-bottom: 1rem;">
                <label for="user_id" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--dark);">
                    Hubungkan ke Akun User <span style="color: var(--gray); font-weight: 400;">(opsional)</span>
                </label>
                <select id="user_id" name="user_id" class="form-input">
                    <option value="">-- Tidak dihubungkan --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->username_nik }} - {{ $user->nama_lengkap }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label for="nik" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--dark);">
                        NIK <span style="color: red;">*</span>
                    </label>
                    <input type="text" id="nik" name="nik" class="form-input"
                        value="{{ old('nik') }}" placeholder="Masukkan NIK" maxlength="20" required>
                </div>
                <div>
                    <label for="nama_lengkap" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--dark);">
                        Nama Lengkap <span style="color: red;">*</span>
                    </label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-input"
                        value="{{ old('nama_lengkap') }}" placeholder="Masukkan nama lengkap" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label for="jenis_kelamin" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--dark);">
                        Jenis Kelamin <span style="color: red;">*</span>
                    </label>
                    <select id="jenis_kelamin" name="jenis_kelamin" class="form-input" required>
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label for="no_telepon" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--dark);">
                        No. Telepon
                    </label>
                    <input type="text" id="no_telepon" name="no_telepon" class="form-input"
                        value="{{ old('no_telepon') }}" placeholder="08xxxxxxxxxx">
                </div>
                <div>
                    <label for="tahun_lulus" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--dark);">
                        Tahun Lulus <span style="color: red;">*</span>
                    </label>
                    <input type="text" id="tahun_lulus" name="tahun_lulus" class="form-input"
                        value="{{ old('tahun_lulus') }}" placeholder="2025" maxlength="4" required>
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="program_pelatihan" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--dark);">
                    Program Pelatihan <span style="color: red;">*</span>
                </label>
                <input type="text" id="program_pelatihan" name="program_pelatihan" class="form-input"
                    value="{{ old('program_pelatihan') }}" placeholder="Masukkan program pelatihan" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <label for="status_bekerja" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--dark);">
                        Status Bekerja <span style="color: red;">*</span>
                    </label>
                    <select id="status_bekerja" name="status_bekerja" class="form-input" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="Bekerja" {{ old('status_bekerja') === 'Bekerja' ? 'selected' : '' }}>Bekerja</option>
                        <option value="Belum Bekerja" {{ old('status_bekerja') === 'Belum Bekerja' ? 'selected' : '' }}>Belum Bekerja</option>
                        <option value="Wirausaha" {{ old('status_bekerja') === 'Wirausaha' ? 'selected' : '' }}>Wirausaha</option>
                    </select>
                </div>
                <div>
                    <label for="status_kuesioner" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--dark);">
                        Status Kuesioner <span style="color: red;">*</span>
                    </label>
                    <select id="status_kuesioner" name="status_kuesioner" class="form-input" required>
                        <option value="Belum" {{ old('status_kuesioner', 'Belum') === 'Belum' ? 'selected' : '' }}>Belum</option>
                        <option value="Sudah" {{ old('status_kuesioner') === 'Sudah' ? 'selected' : '' }}>Sudah</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 0.75rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Data
                </button>
                <a href="{{ route('admin.alumni.index') }}" class="btn btn-outline">
                    Batal
                </a>
            </div>
        </form>

    </div>
</div>
@endsection
