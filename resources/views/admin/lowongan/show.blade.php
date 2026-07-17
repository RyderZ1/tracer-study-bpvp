@extends('layouts.app')

@section('title', 'Detail Lowongan - Tracer Study')
@section('page_title', 'Detail Lowongan Kerja')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">Informasi Lowongan</div>
        <div class="flex gap-2">
            <a href="{{ route('admin.lowongan.edit', $lowongan->id) }}" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.lowongan.index') }}" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <table style="width: 100%; border-collapse: collapse;">
            <tr style="border-bottom: 1px solid var(--light);">
                <td style="padding: 0.75rem 1rem; font-weight: 600; width: 200px; color: var(--gray);">Posisi</td>
                <td style="padding: 0.75rem 1rem;">{{ $lowongan->posisi }}</td>
            </tr>
            <tr style="border-bottom: 1px solid var(--light);">
                <td style="padding: 0.75rem 1rem; font-weight: 600; color: var(--gray);">Perusahaan</td>
                <td style="padding: 0.75rem 1rem;">{{ $lowongan->perusahaan }}</td>
            </tr>
            <tr style="border-bottom: 1px solid var(--light);">
                <td style="padding: 0.75rem 1rem; font-weight: 600; color: var(--gray);">Lokasi</td>
                <td style="padding: 0.75rem 1rem;">{{ $lowongan->lokasi ?? '-' }}</td>
            </tr>
            <tr style="border-bottom: 1px solid var(--light);">
                <td style="padding: 0.75rem 1rem; font-weight: 600; color: var(--gray);">Batas Waktu</td>
                <td style="padding: 0.75rem 1rem;">{{ \Carbon\Carbon::parse($lowongan->batas_waktu)->format('d F Y') }}</td>
            </tr>
            <tr style="border-bottom: 1px solid var(--light);">
                <td style="padding: 0.75rem 1rem; font-weight: 600; color: var(--gray);">Status</td>
                <td style="padding: 0.75rem 1rem;">
                    @if($lowongan->status === 'Aktif')
                        <span class="badge badge-success">Aktif</span>
                    @else
                        <span class="badge" style="background: #f8d7da; color: #721c24;">Tutup</span>
                    @endif
                </td>
            </tr>
            <tr style="border-bottom: 1px solid var(--light);">
                <td style="padding: 0.75rem 1rem; font-weight: 600; color: var(--gray); vertical-align: top;">Deskripsi</td>
                <td style="padding: 0.75rem 1rem; white-space: pre-wrap;">{{ $lowongan->deskripsi ?? 'Tidak ada deskripsi.' }}</td>
            </tr>
            <tr style="border-bottom: 1px solid var(--light);">
                <td style="padding: 0.75rem 1rem; font-weight: 600; color: var(--gray);">Dibuat Pada</td>
                <td style="padding: 0.75rem 1rem;">{{ $lowongan->created_at->format('d M Y, H:i') }}</td>
            </tr>
            <tr>
                <td style="padding: 0.75rem 1rem; font-weight: 600; color: var(--gray);">Terakhir Diperbarui</td>
                <td style="padding: 0.75rem 1rem;">{{ $lowongan->updated_at->format('d M Y, H:i') }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection
