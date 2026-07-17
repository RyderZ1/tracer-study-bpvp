@extends('layouts.app')

@section('title', 'Detail Akun - Tracer Study')
@section('page_title', 'Detail Akun Pengguna')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">Informasi Akun</div>
        <div class="flex gap-2">
            <a href="{{ route('admin.akun.edit', $akun->id) }}" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.akun.index') }}" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <table style="width: 100%; border-collapse: collapse;">
            <tr style="border-bottom: 1px solid var(--light);">
                <td style="padding: 0.75rem 1rem; font-weight: 600; width: 200px; color: var(--gray);">Username / NIK</td>
                <td style="padding: 0.75rem 1rem;">{{ $akun->username_nik }}</td>
            </tr>
            <tr style="border-bottom: 1px solid var(--light);">
                <td style="padding: 0.75rem 1rem; font-weight: 600; color: var(--gray);">Nama Lengkap</td>
                <td style="padding: 0.75rem 1rem;">{{ $akun->nama_lengkap }}</td>
            </tr>
            <tr style="border-bottom: 1px solid var(--light);">
                <td style="padding: 0.75rem 1rem; font-weight: 600; color: var(--gray);">Role</td>
                <td style="padding: 0.75rem 1rem;">
                    @if($akun->role === 'Admin')
                        <span class="badge badge-primary">Admin</span>
                    @else
                        <span class="badge" style="background: var(--light); color: var(--gray);">User</span>
                    @endif
                </td>
            </tr>
            <tr style="border-bottom: 1px solid var(--light);">
                <td style="padding: 0.75rem 1rem; font-weight: 600; color: var(--gray);">Status</td>
                <td style="padding: 0.75rem 1rem;">
                    @if($akun->status === 'Aktif')
                        <span class="badge badge-success">Aktif</span>
                    @else
                        <span class="badge" style="background: #f8d7da; color: #721c24;">Nonaktif</span>
                    @endif
                </td>
            </tr>
            <tr style="border-bottom: 1px solid var(--light);">
                <td style="padding: 0.75rem 1rem; font-weight: 600; color: var(--gray);">Dibuat Pada</td>
                <td style="padding: 0.75rem 1rem;">{{ $akun->created_at->format('d M Y, H:i') }}</td>
            </tr>
            <tr>
                <td style="padding: 0.75rem 1rem; font-weight: 600; color: var(--gray);">Terakhir Diperbarui</td>
                <td style="padding: 0.75rem 1rem;">{{ $akun->updated_at->format('d M Y, H:i') }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection
