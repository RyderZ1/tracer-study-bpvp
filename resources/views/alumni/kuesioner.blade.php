@extends('layouts.app')

@section('title', 'Isi Kuesioner - Tracer Study')
@section('page_title', 'Kuesioner Tracer Study')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">Formulir Kuesioner Tracer Study</div>
    </div>
    <div class="card-body">
        @if(session('warning'))
            <div class="mb-6 p-4 rounded-md" style="background-color: #fef3c7; color: #b45309; border-left: 4px solid #f59e0b;">
                <div class="flex items-center gap-2 font-semibold">
                    <i class="fas fa-exclamation-triangle"></i> Peringatan
                </div>
                <p class="mt-1 text-sm">{{ session('warning') }}</p>
            </div>
        @endif
        @if($errors->any())
            <div class="mb-6 p-4 rounded-md" style="background-color: #f8d7da; color: #721c24; border-left: 4px solid #f5c6cb;">
                <div class="flex items-center gap-2 font-semibold">
                    <i class="fas fa-exclamation-circle"></i> Error
                </div>
                <ul class="mt-1 text-sm" style="list-style: disc; margin-left: 1.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <p class="text-gray mb-6">Silakan isi kuesioner di bawah ini dengan data yang sebenar-benarnya. Jawaban Anda akan kami rahasiakan dan hanya digunakan untuk keperluan evaluasi.</p>
        
        <form action="{{ route('alumni.kuesioner.store') }}" method="POST">
            @csrf
            
            @forelse($pertanyaans as $p)
                <div class="form-group mb-6">
                    <label class="form-label" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">
                        {{ $p->urutan }}. {{ $p->pertanyaan }}
                        @if($p->wajib) <span style="color: red;">*</span> @endif
                    </label>
                    
                    @if($p->tipe_jawaban === 'Pilihan Ganda')
                        <div class="flex flex-col gap-2 mt-2">
                            @foreach($p->opsiJawaban as $opsi)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="jawaban[{{ $p->id }}]" value="{{ $opsi->opsi }}" style="accent-color: var(--primary);" {{ $p->wajib ? 'required' : '' }}> 
                                {{ $opsi->opsi }}
                            </label>
                            @endforeach
                        </div>
                    @else
                        <textarea name="jawaban[{{ $p->id }}]" class="form-input" rows="3" placeholder="Masukkan jawaban Anda..." {{ $p->wajib ? 'required' : '' }}></textarea>
                    @endif
                </div>
            @empty
                <div style="text-align: center; color: var(--gray); padding: 2rem;">
                    Belum ada pertanyaan kuesioner yang tersedia saat ini.
                </div>
            @endforelse

            @if($pertanyaans->count() > 0)
            <div class="mt-8 flex justify-end gap-4 border-t pt-4" style="border-color: var(--gray-light);">
                <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Kirim Kuesioner</button>
            </div>
            @endif
        </form>
    </div>
</div>
@endsection
