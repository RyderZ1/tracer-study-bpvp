@extends('layouts.app')

@section('title', 'Profil Saya - Tracer Study')
@section('page_title', 'Profil Alumni')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">Edit Profil</div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="mb-6 p-4 rounded-md" style="background-color: #d1fae5; color: #065f46; border-left: 4px solid #10b981;">
                <div class="flex items-center gap-2 font-semibold">
                    <i class="fas fa-check-circle"></i> Berhasil
                </div>
                <p class="mt-1 text-sm">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 rounded-md" style="background-color: #fee2e2; color: #b91c1c; border-left: 4px solid #ef4444;">
                <div class="flex items-center gap-2 font-semibold">
                    <i class="fas fa-times-circle"></i> Error
                </div>
                <p class="mt-1 text-sm">{{ session('error') }}</p>
            </div>
        @endif
        @if($errors->any())
            <div class="mb-6 p-4 rounded-md" style="background-color: #fee2e2; color: #b91c1c; border-left: 4px solid #ef4444;">
                <ul class="list-disc ml-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('alumni.profil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="flex gap-8 items-start mb-8">
                <!-- Foto Profil -->
                <div style="width: 150px; flex-shrink: 0; text-align: center;">
                    <div style="width: 120px; height: 120px; border-radius: 50%; background: var(--gray-light); margin: 0 auto 1rem; overflow: hidden; display: flex; align-items: center; justify-content: center; border: 2px solid var(--primary-light);">
                        @if($alumni->foto)
                            <img src="{{ asset('storage/' . $alumni->foto) }}" alt="Foto Profil" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i class="fas fa-user text-gray" style="font-size: 3rem;"></i>
                        @endif
                    </div>
                    <div>
                        <label for="foto" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.875rem; cursor: pointer; display: inline-block;">
                            <i class="fas fa-camera"></i> Ubah Foto
                        </label>
                        <input type="file" id="foto" name="foto" class="hidden" accept="image/jpeg,image/png,image/jpg" onchange="previewImage(this)">
                        <p style="font-size: 0.75rem; color: var(--gray); margin-top: 0.5rem;">Format: JPG, PNG. Maks: 2MB.</p>
                    </div>
                </div>

                <!-- Form Data -->
                <div class="flex-grow">
                    <h3 class="font-bold mb-4" style="font-size: 1.125rem; border-bottom: 1px solid var(--light); padding-bottom: 0.5rem;">Informasi Dasar</h3>
                    
                    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div class="form-group">
                            <label class="form-label">Nomor Induk Kependudukan (NIK)</label>
                            <input type="text" class="form-input" value="{{ $alumni->nik }}" readonly style="background: #f8fafc; color: var(--gray); cursor: not-allowed;">
                            <small class="text-gray" style="font-size: 0.75rem;">NIK tidak dapat diubah (sebagai identitas utama).</small>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-input" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required>
                        </div>
                    </div>

                    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div class="form-group">
                            <label class="form-label">Tahun Lulus</label>
                            <input type="text" class="form-input" value="{{ $alumni->tahun_lulus }}" readonly style="background: #f8fafc; color: var(--gray); cursor: not-allowed;">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Program Pelatihan</label>
                            <input type="text" class="form-input" value="{{ $alumni->program_pelatihan }}" readonly style="background: #f8fafc; color: var(--gray); cursor: not-allowed;">
                        </div>
                    </div>

                    <h3 class="font-bold mb-4 mt-8" style="font-size: 1.125rem; border-bottom: 1px solid var(--light); padding-bottom: 0.5rem;">Informasi Kontak</h3>
                    
                    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div class="form-group">
                            <label class="form-label">Alamat Email</label>
                            <input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" placeholder="Contoh: alumni@example.com">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nomor Telepon / WhatsApp</label>
                            <input type="text" name="no_telepon" class="form-input" value="{{ old('no_telepon', $alumni->no_telepon) }}" placeholder="08123456789">
                        </div>
                    </div>

                    <div class="form-group mb-6">
                        <label class="form-label">Alamat Domisili</label>
                        <textarea name="alamat" class="form-input" rows="3" placeholder="Tuliskan alamat lengkap Anda saat ini...">{{ old('alamat', $alumni->alamat) }}</textarea>
                    </div>

                    <h3 class="font-bold mb-4 mt-8" style="font-size: 1.125rem; border-bottom: 1px solid var(--light); padding-bottom: 0.5rem;">Keamanan Akun</h3>
                    
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label class="form-label">Password Lama <span style="color: var(--gray); font-weight: normal; font-size: 0.8rem;">(Wajib diisi jika ingin mengubah password)</span></label>
                        <div style="position: relative;">
                            <input type="password" id="password_lama" name="password_lama" class="form-input" style="padding-right: 2.5rem;" placeholder="Masukkan password lama Anda">
                            <i class="fas fa-eye" id="togglePasswordLamaIcon" onclick="togglePassword('password_lama', 'togglePasswordLamaIcon')" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: var(--gray); cursor: pointer;"></i>
                        </div>
                    </div>

                    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div class="form-group">
                            <label class="form-label">Password Baru</label>
                            <div style="position: relative;">
                                <input type="password" id="password" name="password" class="form-input" style="padding-right: 2.5rem;" placeholder="Kosongkan jika tidak ingin mengubah">
                                <i class="fas fa-eye" id="togglePasswordIcon" onclick="togglePassword('password', 'togglePasswordIcon')" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: var(--gray); cursor: pointer;"></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <div style="position: relative;">
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" style="padding-right: 2.5rem;" placeholder="Ketik ulang password baru">
                                <i class="fas fa-eye" id="togglePasswordConfirmIcon" onclick="togglePassword('password_confirmation', 'togglePasswordConfirmIcon')" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: var(--gray); cursor: pointer;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4 border-t pt-6" style="border-color: var(--light);">
                <a href="{{ route('alumni.dashboard') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                let imgContainer = input.parentElement.previousElementSibling;
                imgContainer.innerHTML = '<img src="' + e.target.result + '" style="width: 100%; height: 100%; object-fit: cover;">';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
