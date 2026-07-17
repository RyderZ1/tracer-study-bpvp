@extends('layouts.app')

@section('title', 'Kelola Lowongan - Tracer Study')
@section('page_title', 'Kelola Lowongan Kerja')

@section('content')

{{-- Notifikasi --}}
@if(session('success'))
<div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; border: 1px solid #c3e6cb;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<div class="card">
    <div class="card-header">
        <div class="card-title">Daftar Lowongan Kerja</div>
        <a href="{{ route('admin.lowongan.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Lowongan
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Posisi</th>
                        <th>Perusahaan</th>
                        <th>Lokasi</th>
                        <th>Batas Waktu</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lowongan as $index => $item)
                    <tr>
                        <td>{{ $lowongan->firstItem() + $index }}</td>
                        <td><strong>{{ $item->posisi }}</strong></td>
                        <td>{{ $item->perusahaan }}</td>
                        <td>{{ $item->lokasi ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->batas_waktu)->format('d M Y') }}</td>
                        <td>
                            @if($item->status === 'Aktif')
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge" style="background: #f8d7da; color: #721c24;">Tutup</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.lowongan.show', $item->id) }}" class="action-btn" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.lowongan.edit', $item->id) }}" class="action-btn" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.lowongan.destroy', $item->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?')">
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
                        <td colspan="7" style="text-align: center; color: var(--gray); padding: 2rem;">
                            <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block;"></i>
                            Belum ada data lowongan kerja.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($lowongan->hasPages())
        <div class="flex justify-between items-center mt-4 text-gray" style="font-size: 0.875rem;">
            <div>Menampilkan {{ $lowongan->firstItem() }} - {{ $lowongan->lastItem() }} dari {{ $lowongan->total() }} data</div>
            <div class="flex gap-2">
                {{ $lowongan->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
