@extends('layouts.app')

@section('title', 'Dashboard Admin - Tracer Study')
@section('page_title', 'Dashboard Administrator')

@section('content')



<!-- Stat Widgets -->
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Total Alumni -->
    <div class="stat-card" style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 1.5rem;">
        <div class="stat-icon" style="width: 56px; height: 56px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; background: #e0e7ff; color: #4f46e5;">
            <i class="fas fa-user-graduate"></i>
        </div>
        <div class="stat-info">
            <h3 style="color: var(--gray); font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">Total Alumni</h3>
            <div class="stat-value" style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">{{ $total_alumni }}</div>
        </div>
    </div>

    <!-- Kuesioner Terisi -->
    <div class="stat-card" style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 1.5rem;">
        <div class="stat-icon" style="width: 56px; height: 56px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; background: #dcfce7; color: #16a34a;">
            <i class="fas fa-clipboard-check"></i>
        </div>
        <div class="stat-info">
            <h3 style="color: var(--gray); font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">Kuesioner Terisi</h3>
            <div class="stat-value" style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">{{ $kuesioner_terisi }}</div>
        </div>
    </div>

    <!-- Persentase Partisipasi -->
    <div class="stat-card" style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 1.5rem;">
        <div class="stat-icon" style="width: 56px; height: 56px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; background: #fef08a; color: #ca8a04;">
            <i class="fas fa-chart-pie"></i>
        </div>
        <div class="stat-info">
            <h3 style="color: var(--gray); font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">Partisipasi Kuesioner</h3>
            <div class="stat-value" style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">{{ $persentase_kuesioner }}%</div>
        </div>
    </div>

    <!-- Lowongan Aktif -->
    <div class="stat-card" style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 1.5rem;">
        <div class="stat-icon" style="width: 56px; height: 56px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; background: #fee2e2; color: #dc2626;">
            <i class="fas fa-briefcase"></i>
        </div>
        <div class="stat-info">
            <h3 style="color: var(--gray); font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">Lowongan Aktif</h3>
            <div class="stat-value" style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">{{ $lowongan_aktif }}</div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
    <!-- Recent Activity Table -->
    <div class="card" style="margin-bottom: 0;">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-history text-primary"></i> Aktivitas Terbaru (Kuesioner Masuk)</div>
            <a href="{{ route('admin.alumni.index') }}" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">
                Lihat Semua
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Alumni</th>
                            <th>Program Pelatihan</th>
                            <th>Status Kerja</th>
                            <th>Waktu Isi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aktivitas_terbaru as $aktivitas)
                        <tr>
                            <td><strong>{{ $aktivitas->nama_lengkap }}</strong></td>
                            <td>{{ $aktivitas->program_pelatihan }}</td>
                            <td>
                                @if($aktivitas->status_bekerja === 'Bekerja')
                                    <span class="badge badge-success">Bekerja</span>
                                @elseif($aktivitas->status_bekerja === 'Wirausaha')
                                    <span class="badge badge-primary">Wirausaha</span>
                                @else
                                    <span class="badge" style="background:#fff3cd;color:#856404;">Belum Bekerja</span>
                                @endif
                            </td>
                            <td>{{ $aktivitas->updated_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--gray); padding: 2rem;">
                                Belum ada data kuesioner masuk.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Charts / Summaries -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <!-- Chart 1: Status Bekerja -->
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-chart-bar text-success"></i> Sebaran Status Bekerja</div>
            </div>
            <div class="card-body">
                <ul style="list-style: none; padding: 0; margin: 0;">
                    @foreach($data_status_bekerja as $ds)
                    <li style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid var(--light);">
                        <span style="font-weight: 500;">{{ $ds->status_bekerja }}</span>
                        <span class="badge" style="background: var(--light); color: var(--dark);">{{ $ds->count }} Alumni</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Chart 2: Lulusan per Tahun -->
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-chart-line text-warning"></i> Lulusan per Tahun</div>
            </div>
            <div class="card-body">
                <ul style="list-style: none; padding: 0; margin: 0;">
                    @foreach($data_per_tahun as $dt)
                    <li style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid var(--light);">
                        <span style="font-weight: 500;">Tahun {{ $dt->tahun_lulus }}</span>
                        <span class="badge" style="background: var(--light); color: var(--dark);">{{ $dt->count }} Alumni</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection
