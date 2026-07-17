@extends('layouts.app')

@section('title', 'Edit Akun - Tracer Study')
@section('page_title', 'Edit Akun Pengguna')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">Form Edit Akun</div>
        <a href="{{ route('admin.akun.index') }}" class="btn btn-outline">
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

        <form action="{{ route('admin.akun.update', $akun->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 1rem;">
                <label for="username_nik" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--dark);">
                    Username / NIK <span style="color: red;">*</span>
                </label>
                <input type="text" id="username_nik" name="username_nik" class="form-input" 
                    value="{{ old('username_nik', $akun->username_nik) }}" placeholder="Masukkan username atau NIK" required>
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="nama_lengkap" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--dark);">
                    Nama Lengkap <span style="color: red;">*</span>
                </label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-input" 
                    value="{{ old('nama_lengkap', $akun->nama_lengkap) }}" placeholder="Masukkan nama lengkap" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label for="role" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--dark);">
                        Role <span style="color: red;">*</span>
                    </label>
                    <select id="role" name="role" class="form-input" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="Admin" {{ old('role', $akun->role) === 'Admin' ? 'selected' : '' }}>Admin</option>
                        <option value="User" {{ old('role', $akun->role) === 'User' ? 'selected' : '' }}>User</option>
                    </select>
                </div>
                <div>
                    <label for="status" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--dark);">
                        Status <span style="color: red;">*</span>
                    </label>
                    <select id="status" name="status" class="form-input" required>
                        <option value="Aktif" {{ old('status', $akun->status) === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Nonaktif" {{ old('status', $akun->status) === 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <label for="password" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--dark);">
                        Password <span style="color: var(--gray); font-weight: 400;">(Kosongkan jika tidak ingin mengubah)</span>
                    </label>
                    <div style="position: relative;">
                        <input type="password" id="password" name="password" class="form-input" style="padding-right: 2.5rem;"
                            placeholder="Masukkan password baru">
                        <i class="fas fa-eye" id="togglePasswordIcon" onclick="togglePassword('password', 'togglePasswordIcon')" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: var(--gray); cursor: pointer;"></i>
                    </div>
                </div>
                <div>
                    <label for="password_confirmation" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--dark);">
                        Konfirmasi Password
                    </label>
                    <div style="position: relative;">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" style="padding-right: 2.5rem;"
                            placeholder="Ulangi password baru">
                        <i class="fas fa-eye" id="togglePasswordConfirmIcon" onclick="togglePassword('password_confirmation', 'togglePasswordConfirmIcon')" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: var(--gray); cursor: pointer;"></i>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 0.75rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Perbarui Akun
                </button>
                <a href="{{ route('admin.akun.index') }}" class="btn btn-outline">
                    Batal
                </a>
            </div>
        </form>

    </div>
</div>
@endsection
