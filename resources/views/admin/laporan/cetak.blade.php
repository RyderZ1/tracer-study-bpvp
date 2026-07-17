<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Tracer Study</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        .text-center { text-align: center; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-4 { margin-bottom: 1rem; }
        .mt-4 { margin-top: 1rem; }
        .header {
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h2, .header h3 {
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 14px;
        }
        table, th, td {
            border: 1px solid #333;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .summary-box {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .summary-item {
            border: 1px solid #333;
            padding: 10px 20px;
            text-align: center;
            width: 22%;
        }
        .summary-item h4 {
            margin: 0 0 5px 0;
            font-size: 14px;
        }
        .summary-item p {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .summary-item small {
            display: block;
            font-size: 12px;
            color: #666;
        }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="header text-center">
        <h2>Laporan Tracer Study</h2>
        <h3>Sistem Informasi Tracer Study</h3>
        <p style="margin:5px 0 0 0;">
            Filter: 
            <strong>Tahun Lulus:</strong> {{ $tahun_lulus ?: 'Semua Tahun' }} | 
            <strong>Program Pelatihan:</strong> {{ $program_pelatihan ?: 'Semua Program' }}
        </p>
    </div>

    <h4 class="mb-2">Rekapitulasi Data</h4>
    <div class="summary-box">
        <div class="summary-item">
            <h4>Total Alumni</h4>
            <p>{{ $total_alumni }}</p>
        </div>
        <div class="summary-item">
            <h4>Bekerja</h4>
            <p>{{ $jumlah_bekerja }}</p>
            <small>({{ $persentase_bekerja }}%)</small>
        </div>
        <div class="summary-item">
            <h4>Wirausaha</h4>
            <p>{{ $jumlah_wirausaha }}</p>
            <small>({{ $persentase_wirausaha }}%)</small>
        </div>
        <div class="summary-item">
            <h4>Belum Bekerja</h4>
            <p>{{ $jumlah_belum }}</p>
            <small>({{ $persentase_belum }}%)</small>
        </div>
    </div>

    <h4 class="mb-2 mt-4">Daftar Alumni</h4>
    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="15%">NIK</th>
                <th width="25%">Nama Lengkap</th>
                <th width="10%">Tahun Lulus</th>
                <th width="25%">Program Pelatihan</th>
                <th width="20%">Status Bekerja</th>
            </tr>
        </thead>
        <tbody>
            @forelse($alumni as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->nik }}</td>
                <td>{{ $item->nama_lengkap }}</td>
                <td>{{ $item->tahun_lulus }}</td>
                <td>{{ $item->program_pelatihan }}</td>
                <td>{{ $item->status_bekerja }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data alumni untuk filter yang dipilih.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4" style="text-align: right; font-size: 14px;">
        <p>Dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
