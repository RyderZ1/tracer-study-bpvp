@extends('layouts.app')

@section('title', 'Kelola Akun - Tracer Study')
@section('page_title', 'Kelola Akun Pengguna')

@section('content')

@push('styles')
<style>
    @keyframes pulse-danger {
        0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
        70% { box-shadow: 0 0 0 6px rgba(220, 53, 69, 0); }
        100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
    }
    .btn-pulse {
        animation: pulse-danger 2s infinite;
    }
</style>
@endpush

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

<div class="card">
    <div class="card-header">
        <div class="card-title">Daftar Akun Pengguna</div>
        <div class="flex gap-2">
            <form action="{{ route('admin.akun.sync') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mensinkronkan data alumni menjadi akun pengguna? Data yang disinkronisasi tidak akan menduplikasi NIK yang sudah terdaftar.')">
                @csrf
                <button type="submit" class="btn btn-outline">
                    <i class="fas fa-sync"></i> Sinkronisasi Data
                </button>
            </form>
            <a href="{{ route('admin.akun.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Akun
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username / NIK</th>
                        <th>Nama Lengkap</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                    <tr>
                        <td>{{ $users->firstItem() + $index }}</td>
                        <td>{{ $user->username_nik }}</td>
                        <td><strong>{{ $user->nama_lengkap }}</strong></td>
                        <td>
                            @if($user->role === 'Admin')
                                <span class="badge badge-primary">Admin</span>
                            @else
                                <span class="badge" style="background: var(--light); color: var(--gray);">User</span>
                            @endif
                        </td>
                        <td>
                            @if($user->status === 'Aktif')
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge" style="background: #f8d7da; color: #721c24;">Nonaktif</span>
                            @endif

                            @if($user->is_requesting_reset)
                                <div style="margin-top: 6px;">
                                    <span class="badge" style="background: #fee2e2; color: #ef4444; font-size: 0.7rem; padding: 4px 8px; border: 1px solid #fca5a5;">
                                        <i class="fas fa-exclamation-circle"></i> Meminta Reset
                                    </span>
                                </div>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.akun.edit', $user->id) }}" class="action-btn" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.akun.resetPassword', $user->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin me-reset password pengguna ini kembali ke default (sama dengan NIK/Username)?')">
                                @csrf
                                @if($user->is_requesting_reset)
                                    <button type="submit" class="action-btn btn-pulse" style="color: white; background: #ef4444; border-radius: 50%; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;" title="Segera Reset Password!">
                                        <i class="fas fa-key"></i>
                                    </button>
                                @else
                                    <button type="submit" class="action-btn" style="color: #64748b; background: rgba(100,116,139,0.1);" title="Reset Password">
                                        <i class="fas fa-key"></i>
                                    </button>
                                @endif
                            </form>
                            <form action="{{ route('admin.akun.destroy', $user->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?')">
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
                        <td colspan="6" style="text-align: center; color: var(--gray); padding: 2rem;">
                            <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block;"></i>
                            Belum ada data akun.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
        <div class="flex justify-between items-center mt-4 text-gray" style="font-size: 0.875rem;">
            <div>Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} data</div>
            <div class="flex gap-2">
                {{ $users->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
