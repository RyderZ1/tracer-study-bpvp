@extends('layouts.app')

@section('title', 'Laporan Tracer Study - Admin')
@section('page_title', 'Laporan Tracer Study')

@section('content')

<div class="card mb-4">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-filter"></i> Filter Laporan</div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.laporan.index') }}" method="GET" class="flex gap-4 items-end">
            <div>
                <label for="tahun_lulus" style="display:block;margin-bottom:0.5rem;font-weight:500;">Tahun Lulus</label>
                <select name="tahun_lulus" id="tahun_lulus" class="form-input" style="width:200px;">
                    <option value="">Semua Tahun</option>
                    @foreach($list_tahun as $thn)
                        <option value="{{ $thn }}" {{ $tahun_lulus == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="program_pelatihan" style="display:block;margin-bottom:0.5rem;font-weight:500;">Program Pelatihan</label>
                <select name="program_pelatihan" id="program_pelatihan" class="form-input" style="width:250px;">
                    <option value="">Semua Program</option>
                    @foreach($list_program as $prog)
                        <option value="{{ $prog }}" {{ $program_pelatihan == $prog ? 'selected' : '' }}>{{ $prog }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Tampilkan</button>
                <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline"><i class="fas fa-redo"></i> Reset</a>
                <a href="{{ route('admin.laporan.cetak', ['tahun_lulus' => request('tahun_lulus'), 'program_pelatihan' => request('program_pelatihan')]) }}" target="_blank" class="btn btn-success" style="background-color: #10b981; color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.375rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;"><i class="fas fa-print"></i> Cetak Laporan</a>
                <a href="{{ route('admin.laporan.download', ['tahun_lulus' => request('tahun_lulus'), 'program_pelatihan' => request('program_pelatihan')]) }}" class="btn" style="background-color: #f59e0b; color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.375rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;"><i class="fas fa-file-csv"></i> Download CSV</a>
            </div>
        </form>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Total Alumni -->
    <div class="stat-card" style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 1.5rem;">
        <div class="stat-icon" style="width: 56px; height: 56px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; background: #e0e7ff; color: #4f46e5;">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <h3 style="color: var(--gray); font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">Total Alumni</h3>
            <div class="stat-value" style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">{{ $total_alumni }}</div>
        </div>
    </div>

    <!-- Bekerja -->
    <div class="stat-card" style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 1.5rem;">
        <div class="stat-icon" style="width: 56px; height: 56px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; background: #dcfce7; color: #16a34a;">
            <i class="fas fa-briefcase"></i>
        </div>
        <div class="stat-info">
            <h3 style="color: var(--gray); font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">Bekerja</h3>
            <div class="stat-value" style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">{{ $jumlah_bekerja }} <span style="font-size:1rem;color:var(--gray);">({{ $persentase_bekerja }}%)</span></div>
        </div>
    </div>

    <!-- Wirausaha -->
    <div class="stat-card" style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 1.5rem;">
        <div class="stat-icon" style="width: 56px; height: 56px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; background: #fef08a; color: #ca8a04;">
            <i class="fas fa-store"></i>
        </div>
        <div class="stat-info">
            <h3 style="color: var(--gray); font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">Wirausaha</h3>
            <div class="stat-value" style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">{{ $jumlah_wirausaha }} <span style="font-size:1rem;color:var(--gray);">({{ $persentase_wirausaha }}%)</span></div>
        </div>
    </div>

    <!-- Belum Bekerja -->
    <div class="stat-card" style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 1.5rem;">
        <div class="stat-icon" style="width: 56px; height: 56px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; background: #fee2e2; color: #dc2626;">
            <i class="fas fa-user-times"></i>
        </div>
        <div class="stat-info">
            <h3 style="color: var(--gray); font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">Belum Bekerja</h3>
            <div class="stat-value" style="font-size: 1.5rem; font-weight: 700; color: var(--dark);">{{ $jumlah_belum }} <span style="font-size:1rem;color:var(--gray);">({{ $persentase_belum }}%)</span></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-chart-pie"></i> Visualisasi Data</div>
    </div>
    <div class="card-body">
        <div style="height: 300px; display:flex; align-items:center; justify-content:center; background:#f8fafc; border-radius:8px; border:1px dashed #cbd5e1;">
            <span style="color:var(--gray);"><i class="fas fa-image" style="font-size:2rem;display:block;text-align:center;margin-bottom:0.5rem;"></i>Area Chart / Grafik (Dapat menggunakan Chart.js nantinya)</span>
        </div>
    </div>
</div>

@endsection
