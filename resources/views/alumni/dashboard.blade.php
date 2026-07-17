@extends('layouts.app')

@section('title', 'Alumni Dashboard - Tracer Study')
@section('page_title', 'Selamat Datang, Alumni!')

@section('content')
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

<div class="card bg-primary text-white" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%); color: white;">
    <div class="card-body">
        <h3 class="font-bold" style="font-size: 1.5rem; margin-bottom: 0.5rem; color: white;">Hai, {{ Auth::check() ? Auth::user()->nama_lengkap : session('name', 'Alumni') }}!</h3>
        <p style="opacity: 0.9; margin-bottom: 1.5rem;">Terima kasih telah berpartisipasi dalam Tracer Study BPVP Sidoarjo. Data yang Anda berikan sangat berarti untuk evaluasi dan pengembangan program pelatihan kami.</p>
        <div class="flex gap-4">
            @if($status_kuesioner !== 'Sudah')
                <a href="{{ route('alumni.kuesioner.index') }}" class="btn" style="background: white; color: var(--primary);">
                    <i class="fas fa-clipboard-check"></i> Isi Kuesioner Sekarang
                </a>
            @else
                <button class="btn" style="background: #10b981; color: white; cursor: default;">
                    <i class="fas fa-check"></i> Kuesioner Selesai
                </button>
            @endif
            <a href="{{ route('alumni.lowongan.index') }}" class="btn" style="background: rgba(255,255,255,0.2); color: white;">
                <i class="fas fa-briefcase"></i> Lihat Lowongan
            </a>
        </div>
    </div>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon bg-blue-light">
            <i class="fas fa-clipboard-list"></i>
        </div>
        <div class="stat-info">
            <h4>Status Kuesioner</h4>
            @if($status_kuesioner === 'Sudah')
                <div class="value" style="font-size: 1.25rem; color: #10b981; margin-top: 0.25rem;">Sudah Diisi</div>
            @else
                <div class="value" style="font-size: 1.25rem; color: var(--warning); margin-top: 0.25rem;">Belum Diisi</div>
            @endif
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-purple-light">
            <i class="fas fa-building"></i>
        </div>
        <div class="stat-info">
            <h4>Lowongan Baru</h4>
            <div class="value">{{ $lowongan_baru }}</div>
        </div>
    </div>
</div>
@endsection
