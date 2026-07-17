@extends('layouts.app')

@section('title', 'Kelola Alumni - Tracer Study')
@section('page_title', 'Kelola Data Alumni')

@section('content')

{{-- Notifikasi --}}
@if(session('success'))
<div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; border: 1px solid #c3e6cb;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; border: 1px solid #f5c6cb;">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
</div>
@endif

@if(session('info'))
<div style="background: #d1ecf1; color: #0c5460; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; border: 1px solid #bee5eb;">
    <i class="fas fa-info-circle"></i> {{ session('info') }}
</div>
@endif

<div class="card">
    <div class="card-header">
        <div class="card-title">Daftar Data Alumni</div>
        <div class="flex gap-2">
            {{-- Tombol Import --}}
            <button type="button" class="btn btn-outline" onclick="document.getElementById('importModal').style.display='flex'">
                <i class="fas fa-file-excel text-success"></i> Import
            </button>
            {{-- Tombol Download Template --}}
            <a href="{{ route('admin.alumni.exportTemplate') }}" class="btn btn-outline">
                <i class="fas fa-download"></i> Template
            </a>
            {{-- Tombol Tambah --}}
            <a href="{{ route('admin.alumni.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Alumni
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Nama Lengkap</th>
                        <th>L/P</th>
                        <th>Tahun Lulus</th>
                        <th>Program Pelatihan</th>
                        <th>Status Bekerja</th>
                        <th>Kuesioner</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alumni as $index => $item)
                    <tr>
                        <td>{{ $alumni->firstItem() + $index }}</td>
                        <td>{{ $item->nik }}</td>
                        <td><strong>{{ $item->nama_lengkap }}</strong></td>
                        <td>{{ $item->jenis_kelamin }}</td>
                        <td>{{ $item->tahun_lulus }}</td>
                        <td>{{ $item->program_pelatihan }}</td>
                        <td>
                            @if($item->status_bekerja === 'Bekerja')
                                <span class="badge badge-success">Bekerja</span>
                            @elseif($item->status_bekerja === 'Wirausaha')
                                <span class="badge badge-primary">Wirausaha</span>
                            @else
                                <span class="badge" style="background: #fff3cd; color: #856404;">Belum Bekerja</span>
                            @endif
                        </td>
                        <td>
                            @if($item->status_kuesioner === 'Sudah')
                                <span class="badge badge-success">Sudah</span>
                            @else
                                <span class="badge" style="background: var(--light); color: var(--gray);">Belum</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.alumni.show', $item->id) }}" class="action-btn" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.alumni.edit', $item->id) }}" class="action-btn" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.alumni.destroy', $item->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data alumni ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="text-align: center; color: var(--gray); padding: 2rem;">
                            <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block;"></i>
                            Belum ada data alumni.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($alumni->hasPages())
        <div class="flex justify-between items-center mt-4 text-gray" style="font-size: 0.875rem;">
            <div>Menampilkan {{ $alumni->firstItem() }} - {{ $alumni->lastItem() }} dari {{ $alumni->total() }} data</div>
            <div class="flex gap-2">
                {{ $alumni->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Modal Import --}}
<div id="importModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 1.5rem; width: 100%; max-width: 500px; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="margin: 0; font-size: 1.125rem; font-weight: 600;">Import Data Alumni</h3>
            <button onclick="document.getElementById('importModal').style.display='none'" style="background: none; border: none; font-size: 1.25rem; cursor: pointer; color: var(--gray);">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('admin.alumni.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">File CSV <span style="color: red;">*</span></label>
                <input type="file" name="file" class="form-input" accept=".csv" required>
                <small style="color: var(--gray); display: block; margin-top: 0.25rem;">
                    Format yang didukung: .csv (maks. 2MB)
                </small>
            </div>
            <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('importModal').style.display='none'">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Import</button>
            </div>
        </form>
    </div>
</div>
@endsection
