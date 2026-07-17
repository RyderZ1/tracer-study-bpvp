@extends('layouts.app')

@section('title', 'Tambah Pertanyaan - Tracer Study')
@section('page_title', 'Tambah Pertanyaan Kuesioner')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">Form Tambah Pertanyaan</div>
        <a href="{{ route('admin.kuesioner.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body">
        @if($errors->any())
        <div style="background:#f8d7da;color:#721c24;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1rem;border:1px solid #f5c6cb;">
            <ul style="margin:0;padding-left:1.25rem;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.kuesioner.store') }}" method="POST">
            @csrf

            <div style="margin-bottom:1rem;">
                <label for="pertanyaan" style="display:block;margin-bottom:0.5rem;font-weight:500;">Pertanyaan <span style="color:red;">*</span></label>
                <textarea id="pertanyaan" name="pertanyaan" class="form-input" rows="3" required>{{ old('pertanyaan') }}</textarea>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-bottom:1rem;">
                <div>
                    <label for="tipe_jawaban" style="display:block;margin-bottom:0.5rem;font-weight:500;">Tipe Jawaban <span style="color:red;">*</span></label>
                    <select id="tipe_jawaban" name="tipe_jawaban" class="form-input" required onchange="toggleOpsi(this.value)">
                        <option value="Teks Singkat" {{ old('tipe_jawaban') === 'Teks Singkat' ? 'selected' : '' }}>Teks Singkat</option>
                        <option value="Pilihan Ganda" {{ old('tipe_jawaban') === 'Pilihan Ganda' ? 'selected' : '' }}>Pilihan Ganda</option>
                    </select>
                </div>
                <div>
                    <label for="urutan" style="display:block;margin-bottom:0.5rem;font-weight:500;">Urutan <span style="color:red;">*</span></label>
                    <input type="number" id="urutan" name="urutan" class="form-input" value="{{ old('urutan', 1) }}" required min="1">
                </div>
                <div style="display:flex;align-items:flex-end;padding-bottom:0.5rem;">
                    <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;">
                        <input type="checkbox" name="wajib" value="1" {{ old('wajib', true) ? 'checked' : '' }}>
                        <span style="font-weight:500;">Wajib Diisi</span>
                    </label>
                </div>
            </div>

            <div id="opsi-container" style="display:{{ old('tipe_jawaban') === 'Pilihan Ganda' ? 'block' : 'none' }};margin-bottom:1.5rem;background:#f8fafc;padding:1rem;border-radius:8px;border:1px solid #e2e8f0;">
                <label style="display:block;margin-bottom:0.5rem;font-weight:500;">Opsi Jawaban</label>
                <div id="opsi-list">
                    @if(old('opsi'))
                        @foreach(old('opsi') as $index => $opsi)
                        <div class="opsi-item flex gap-2 mb-2">
                            <input type="text" name="opsi[]" class="form-input" value="{{ $opsi }}" placeholder="Masukkan opsi jawaban">
                            <button type="button" class="btn btn-outline delete-opsi" style="color:red;border-color:red;" onclick="removeOpsi(this)"><i class="fas fa-times"></i></button>
                        </div>
                        @endforeach
                    @else
                        <div class="opsi-item flex gap-2 mb-2">
                            <input type="text" name="opsi[]" class="form-input" placeholder="Masukkan opsi jawaban">
                            <button type="button" class="btn btn-outline delete-opsi" style="color:red;border-color:red;" onclick="removeOpsi(this)"><i class="fas fa-times"></i></button>
                        </div>
                    @endif
                </div>
                <button type="button" class="btn btn-outline mt-2" onclick="addOpsi()" style="font-size:0.875rem;padding:0.25rem 0.75rem;"><i class="fas fa-plus"></i> Tambah Opsi</button>
            </div>

            <div style="display:flex;gap:0.75rem;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Pertanyaan</button>
                <a href="{{ route('admin.kuesioner.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function toggleOpsi(tipe) {
        document.getElementById('opsi-container').style.display = tipe === 'Pilihan Ganda' ? 'block' : 'none';
    }

    function addOpsi() {
        const html = `
            <div class="opsi-item flex gap-2 mb-2">
                <input type="text" name="opsi[]" class="form-input" placeholder="Masukkan opsi jawaban">
                <button type="button" class="btn btn-outline delete-opsi" style="color:red;border-color:red;" onclick="removeOpsi(this)"><i class="fas fa-times"></i></button>
            </div>
        `;
        document.getElementById('opsi-list').insertAdjacentHTML('beforeend', html);
    }

    function removeOpsi(btn) {
        const items = document.querySelectorAll('.opsi-item');
        if(items.length > 1) {
            btn.parentElement.remove();
        } else {
            alert('Minimal harus ada 1 opsi jawaban');
        }
    }
</script>
@endpush
@endsection
