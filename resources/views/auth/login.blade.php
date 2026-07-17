@extends('layouts.app')

@section('layout', 'auth')
@section('title', 'Login - Tracer Study BPVP Sidoarjo')

@push('styles')
<style>
    /* Styling Dasar Halaman Login agar presisi di tengah */
    .auth-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        padding: 1rem;
    }

    .auth-card {
        width: 100%;
        max-width: 450px; /* Diperlebar sedikit agar proporsional */
        background: rgba(255, 255, 255, 0.95);
        border-radius: 1rem;
        padding: 2.5rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.5);
    }

    .auth-logo {
        width: 72px; 
        height: 72px; 
        background: #e0e7ff; 
        color: #4f46e5; 
        border-radius: 50%; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-size: 2.2rem; 
        margin: 0 auto 1.2rem auto;
    }

    .auth-title {
        font-size: 1.5rem; 
        font-weight: 700; 
        color: #0f172a; 
        margin-bottom: 0.5rem;
    }

    .auth-subtitle {
        font-size: 0.9rem;
        color: #64748b;
    }

    .input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-wrapper i.icon-left {
        position: absolute; 
        left: 1.2rem; 
        color: #64748b;
        pointer-events: none;
    }

    .input-wrapper i.icon-right {
        position: absolute; 
        right: 1.2rem; 
        color: #64748b;
        cursor: pointer;
        padding: 0.5rem; /* Perbesar area klik */
    }

    .auth-input {
        width: 100%;
        padding: 0.8rem 1rem 0.8rem 2.8rem;
        border: 1px solid #cbd5e1;
        border-radius: 0.5rem;
        font-size: 0.95rem;
        background-color: #ffffff;
        color: #0f172a;
        transition: all 0.2s;
    }
    
    .auth-input.password-input {
        padding-right: 2.8rem; /* Ruang untuk ikon mata */
    }

    .auth-input:focus {
        outline: none;
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
    }
</style>
@endpush

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="text-center mb-6">
            <div class="auth-logo">
                <i class="fas fa-user-graduate"></i>
            </div>
            <h1 class="auth-title">Tracer Study System</h1>
            <p class="auth-subtitle">BPVP Sidoarjo - Silakan masuk ke akun Anda</p>
        </div>

        @if(session('success'))
            <div style="background-color: #dcfce7; border-left: 4px solid #22c55e; color: #166534; padding: 0.75rem 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem; font-size: 0.875rem;">
                <div class="flex items-center gap-2 font-semibold mb-1">
                    <i class="fas fa-check-circle text-green"></i> Berhasil
                </div>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background-color: #fee2e2; border-left: 4px solid #ef4444; color: #991b1b; padding: 0.75rem 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem; font-size: 0.875rem;">
                <div class="flex items-center gap-2 font-semibold mb-1">
                    <i class="fas fa-exclamation-circle text-red"></i> Login Gagal
                </div>
                <ul style="list-style-type: disc; padding-left: 1.5rem; margin: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/login" method="post">
            @csrf
            <div class="form-group">
                <label for="username" class="form-label font-semibold">Username / NIK</label>
                <div class="input-wrapper">
                    <i class="fas fa-user icon-left"></i>
                    <input type="text" id="username" name="username" class="auth-input" placeholder="Masukkan username atau NIK" required>
                </div>
            </div>

            <div class="form-group mb-6">
                <label for="password" class="form-label font-semibold">Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock icon-left"></i>
                    <input type="password" id="password" name="password" class="auth-input password-input" placeholder="Masukkan password" required>
                    <i class="fas fa-eye icon-right" id="togglePasswordIcon" onclick="togglePassword('password', 'togglePasswordIcon')"></i>
                </div>
                <div class="flex justify-between items-center mt-3">
                    <label class="flex items-center gap-2" style="font-size: 0.85rem; cursor: pointer;">
                        <input type="checkbox" name="remember" style="accent-color: #4f46e5; width: 16px; height: 16px;"> 
                        <span class="auth-subtitle">Ingat Saya</span>
                    </label>
                    <a href="#" onclick="requestPasswordReset(); return false;" style="color: #4f46e5; font-size: 0.85rem; font-weight: 600; text-decoration: none;">Lupa Password?</a>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-full" style="padding: 0.8rem; font-size: 1rem; border-radius: 0.5rem; border: none; background: #4f46e5; color: white; cursor: pointer; transition: background 0.2s; display: flex; justify-content: center; align-items: center;">
                Masuk <i class="fas fa-arrow-right ml-2" style="margin-left: 0.5rem;"></i>
            </button>
        </form>
        
        <!-- Form tersembunyi untuk request reset password -->
        <form id="reset-form" action="{{ route('password.request.admin') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="nik" id="reset-nik-input">
        </form>

        <div class="text-center mt-6 auth-subtitle" style="font-size: 0.85rem;">
            Belum punya akun? Hubungi Admin BPVP Sidoarjo
        </div>
    </div>
</div>

<script>
function requestPasswordReset() {
    let nik = prompt('Masukkan Username/NIK Anda untuk mengirim permintaan reset password ke Admin:');
    if (nik && nik.trim() !== '') {
        document.getElementById('reset-nik-input').value = nik.trim();
        document.getElementById('reset-form').submit();
    }
}
</script>
@endsection
