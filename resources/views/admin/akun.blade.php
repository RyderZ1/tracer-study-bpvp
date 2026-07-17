@extends('layouts.app')

@section('title', 'Kelola Akun - Tracer Study')
@section('page_title', 'Kelola Akun Pengguna')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">Daftar Akun Pengguna</div>
        <button class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Akun
        </button>
    </div>
    <div class="card-body">
        <div class="flex justify-between items-center mb-4">
            <div class="flex gap-2">
                <select class="form-input" style="width: auto;">
                    <option>Semua Role</option>
                    <option>Admin</option>
                    <option>Alumni</option>
                </select>
            </div>
            <div style="position: relative; width: 300px;">
                <i class="fas fa-search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--gray);"></i>
                <input type="text" class="form-input" placeholder="Cari akun..." style="padding-left: 2.5rem;">
            </div>
        </div>
        
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
                    <tr>
                        <td>1</td>
                        <td>admin</td>
                        <td><strong>Administrator Utama</strong></td>
                        <td><span class="badge badge-primary">Admin</span></td>
                        <td><span class="badge badge-success">Aktif</span></td>
                        <td>
                            <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                            <button class="action-btn delete" title="Hapus"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>3515012345678901</td>
                        <td><strong>Budi Santoso</strong></td>
                        <td><span class="badge badge-warning" style="background: var(--light); color: var(--gray);">Alumni</span></td>
                        <td><span class="badge badge-success">Aktif</span></td>
                        <td>
                            <button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button>
                            <button class="action-btn delete" title="Hapus"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="flex justify-between items-center mt-4 text-gray" style="font-size: 0.875rem;">
            <div>Menampilkan 1 - 2 dari 2 data</div>
            <div class="flex gap-2">
                <button class="btn btn-outline" style="padding: 0.25rem 0.75rem;">Sebelumnya</button>
                <button class="btn btn-primary" style="padding: 0.25rem 0.75rem;">1</button>
                <button class="btn btn-outline" style="padding: 0.25rem 0.75rem;">Selanjutnya</button>
            </div>
        </div>
    </div>
</div>
@endsection
