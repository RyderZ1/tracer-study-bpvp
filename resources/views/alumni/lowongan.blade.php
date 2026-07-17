@extends('layouts.app')

@section('title', 'Info Lowongan Kerja - Tracer Study')
@section('page_title', 'Informasi Lowongan Kerja')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div style="position: relative; width: 350px;">
        <i class="fas fa-search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--gray);"></i>
        <input type="text" class="form-input" placeholder="Cari posisi atau perusahaan..." style="padding-left: 2.5rem;" onkeyup="searchJobs(this.value)">
    </div>
</div>

<div class="stat-grid" id="jobs-container">
    @forelse($lowongans as $loker)
    <div class="card job-card" style="margin-bottom: 0;" data-search="{{ strtolower($loker->posisi . ' ' . $loker->perusahaan . ' ' . $loker->lokasi) }}">
        <div class="card-body">
            <div class="flex justify-between items-start mb-4">
                <div style="width: 48px; height: 48px; background: var(--primary-light); color: var(--primary); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                    <i class="fas fa-briefcase"></i>
                </div>
                <span class="badge badge-success">Aktif</span>
            </div>
            <h3 class="font-bold mb-1" style="font-size: 1.125rem;">{{ $loker->posisi }}</h3>
            <p class="text-gray mb-4" style="font-size: 0.875rem;">{{ $loker->perusahaan }}</p>
            
            <div class="flex flex-col gap-2 mb-6" style="font-size: 0.875rem;">
                <div class="flex items-center gap-2 text-gray">
                    <i class="fas fa-map-marker-alt" style="width: 16px;"></i> {{ $loker->lokasi ?? 'Lokasi tidak disebutkan' }}
                </div>
                <div class="flex items-center gap-2 text-gray">
                    <i class="fas fa-calendar-alt" style="width: 16px;"></i> Batas: {{ \Carbon\Carbon::parse($loker->batas_waktu)->format('d M Y') }}
                </div>
            </div>
            
            <button class="btn btn-outline w-full" onclick="showDetail('{{ addslashes($loker->posisi) }}', '{{ addslashes($loker->perusahaan) }}', '{{ addslashes(nl2br($loker->deskripsi ?? 'Tidak ada deskripsi rinci.')) }}')">Lihat Detail</button>
        </div>
    </div>
    @empty
    <div style="grid-column: 1 / -1; text-align: center; color: var(--gray); padding: 3rem;">
        <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
        Belum ada informasi lowongan kerja yang aktif saat ini.
    </div>
    @endforelse
</div>

<!-- Modal Detail (Sederhana via alert sementara) -->
@push('scripts')
<script>
    function searchJobs(query) {
        query = query.toLowerCase();
        let cards = document.querySelectorAll('.job-card');
        cards.forEach(card => {
            let data = card.getAttribute('data-search');
            if (data.includes(query)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function showDetail(posisi, perusahaan, deskripsi) {
        // Mengubah tag <br /> kembali jadi newline agar rapi di alert
        let cleanDesc = deskripsi.replace(/<br\s*[\/]?>/gi, "\n");
        alert("Posisi: " + posisi + "\nPerusahaan: " + perusahaan + "\n\nDeskripsi:\n" + cleanDesc);
    }
</script>
@endpush

@endsection
