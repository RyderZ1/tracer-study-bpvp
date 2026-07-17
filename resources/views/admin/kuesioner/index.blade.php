@extends('layouts.app')

@section('title', 'Kelola Kuesioner - Tracer Study')
@section('page_title', 'Kelola Pertanyaan Kuesioner')

@section('content')

@if(session('success'))
<div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; border: 1px solid #c3e6cb;">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<div class="card">
    <div class="card-header">
        <div class="card-title">Daftar Pertanyaan</div>
        <a href="{{ route('admin.kuesioner.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Pertanyaan
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Urutan</th>
                        <th>Pertanyaan</th>
                        <th>Tipe Jawaban</th>
                        <th>Wajib</th>
                        <th>Opsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pertanyaans as $p)
                    <tr>
                        <td>{{ $p->urutan }}</td>
                        <td><strong>{{ $p->pertanyaan }}</strong></td>
                        <td>{{ $p->tipe_jawaban }}</td>
                        <td>
                            @if($p->wajib)
                                <span class="badge badge-primary">Ya</span>
                            @else
                                <span class="badge" style="background:var(--light);color:var(--gray);">Tidak</span>
                            @endif
                        </td>
                        <td>
                            @if($p->tipe_jawaban === 'Pilihan Ganda')
                                <ul style="margin:0;padding-left:1.2rem;">
                                    @foreach($p->opsiJawaban as $opsi)
                                        <li>{{ $opsi->opsi }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <span style="color:var(--gray);">-</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.kuesioner.edit', $p->id) }}" class="action-btn" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.kuesioner.destroy', $p->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin hapus pertanyaan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete" title="Hapus"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;padding:2rem;">Belum ada pertanyaan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($pertanyaans->hasPages())
        <div class="flex justify-between items-center mt-4 text-gray" style="font-size: 0.875rem;">
            <div>Menampilkan {{ $pertanyaans->firstItem() }} - {{ $pertanyaans->lastItem() }} dari {{ $pertanyaans->total() }} data</div>
            <div class="flex gap-2">{{ $pertanyaans->links() }}</div>
        </div>
        @endif
    </div>
</div>
@endsection
