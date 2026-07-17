@extends('layouts.app')

@section('title', 'Kelola Alumni - Tracer Study')
@section('page_title', 'Kelola Data Alumni')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">Daftar Data Alumni</div>
        <div class="flex gap-2">
            <button class="btn btn-outline">
                <i class="fas fa-file-excel text-success"></i> Import
            </button>
            <button class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Alumni
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama Lengkap</th>
                        <th>Tahun Lulus</th>
                        <th>Program Pelatihan</th>
                        <th>Status Bekerja</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>3515012345678901</td>
                        <td><strong>Budi Santoso</strong></td>
                        <td>2025</td>
                        <td>Desain Grafis</td>
                        <td><span class="badge badge-success">Bekerja</span></td>
                        <td>
                            <button class="action-btn" title="Detail"><i class="fas fa-eye"></i></button>
                            <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>3515012345678902</td>
                        <td><strong>Siti Aminah</strong></td>
                        <td>2025</td>
                        <td>Menjahit Pakaian</td>
                        <td><span class="badge badge-warning">Belum Bekerja</span></td>
                        <td>
                            <button class="action-btn" title="Detail"><i class="fas fa-eye"></i></button>
                            <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
