@extends('layouts.role', ['role' => 'mahasiswa'])

@section('title', 'Detail Perizinan')

@section('content')
<div class="max-w-2xl mx-auto space-y-4">
    <a href="{{ route('mahasiswa.perizinan.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-gray-900">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        <span>Kembali ke Daftar Perizinan</span>
    </a>

    <div class="bg-white rounded-2xl border border-gray-150 p-6 shadow-sm">
        <h1 class="text-lg font-bold text-gray-900 mb-4">Detail Pengajuan Izin</h1>
        {{-- Detail content --}}
    </div>
</div>
@endsection
