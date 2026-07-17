@extends('layouts.app')

@section('title', 'Detail Alumni - Tracer Study')
@section('page_title', 'Detail Data Alumni')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">Informasi Alumni</div>
        <div class="flex gap-2">
            <a href="{{ route('admin.alumni.edit', $alumnus->id) }}" class="btn btn-primary" style="padding:0.4rem 0.8rem;font-size:0.85rem;"><i class="fas fa-edit"></i> Edit</a>
            <a href="{{ route('admin.alumni.index') }}" class="btn btn-outline" style="padding:0.4rem 0.8rem;font-size:0.85rem;"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <table style="width:100%;border-collapse:collapse;">
            <tr style="border-bottom:1px solid var(--light);">
                <td style="padding:0.75rem 1rem;font-weight:600;width:200px;color:var(--gray);">NIK</td>
                <td style="padding:0.75rem 1rem;">{{ $alumnus->nik }}</td>
            </tr>
            <tr style="border-bottom:1px solid var(--light);">
                <td style="padding:0.75rem 1rem;font-weight:600;color:var(--gray);">Nama Lengkap</td>
                <td style="padding:0.75rem 1rem;">{{ $alumnus->nama_lengkap }}</td>
            </tr>
            <tr style="border-bottom:1px solid var(--light);">
                <td style="padding:0.75rem 1rem;font-weight:600;color:var(--gray);">Jenis Kelamin</td>
                <td style="padding:0.75rem 1rem;">{{ $alumnus->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            </tr>
            <tr style="border-bottom:1px solid var(--light);">
                <td style="padding:0.75rem 1rem;font-weight:600;color:var(--gray);">No. Telepon</td>
                <td style="padding:0.75rem 1rem;">{{ $alumnus->no_telepon ?? '-' }}</td>
            </tr>
            <tr style="border-bottom:1px solid var(--light);">
                <td style="padding:0.75rem 1rem;font-weight:600;color:var(--gray);">Tahun Lulus</td>
                <td style="padding:0.75rem 1rem;">{{ $alumnus->tahun_lulus }}</td>
            </tr>
            <tr style="border-bottom:1px solid var(--light);">
                <td style="padding:0.75rem 1rem;font-weight:600;color:var(--gray);">Program Pelatihan</td>
                <td style="padding:0.75rem 1rem;">{{ $alumnus->program_pelatihan }}</td>
            </tr>
            <tr style="border-bottom:1px solid var(--light);">
                <td style="padding:0.75rem 1rem;font-weight:600;color:var(--gray);">Status Bekerja</td>
                <td style="padding:0.75rem 1rem;">
                    @if($alumnus->status_bekerja === 'Bekerja')
                        <span class="badge badge-success">Bekerja</span>
                    @elseif($alumnus->status_bekerja === 'Wirausaha')
                        <span class="badge badge-primary">Wirausaha</span>
                    @else
                        <span class="badge" style="background:#fff3cd;color:#856404;">Belum Bekerja</span>
                    @endif
                </td>
            </tr>
            <tr style="border-bottom:1px solid var(--light);">
                <td style="padding:0.75rem 1rem;font-weight:600;color:var(--gray);">Status Kuesioner</td>
                <td style="padding:0.75rem 1rem;">
                    @if($alumnus->status_kuesioner === 'Sudah')
                        <span class="badge badge-success">Sudah</span>
                    @else
                        <span class="badge" style="background:var(--light);color:var(--gray);">Belum</span>
                    @endif
                </td>
            </tr>
            <tr style="border-bottom:1px solid var(--light);">
                <td style="padding:0.75rem 1rem;font-weight:600;color:var(--gray);">Akun Terhubung</td>
                <td style="padding:0.75rem 1rem;">{{ $alumnus->user ? $alumnus->user->username_nik . ' (' . $alumnus->user->nama_lengkap . ')' : 'Tidak ada' }}</td>
            </tr>
            <tr>
                <td style="padding:0.75rem 1rem;font-weight:600;color:var(--gray);">Dibuat Pada</td>
                <td style="padding:0.75rem 1rem;">{{ $alumnus->created_at->format('d M Y, H:i') }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection
