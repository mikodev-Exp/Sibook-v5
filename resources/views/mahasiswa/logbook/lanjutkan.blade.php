@extends('layouts.role', ['role' => 'mahasiswa'])

@section('title', 'Lanjutkan Logbook')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('mahasiswa.logbook.index') }}" class="p-2 bg-white rounded-lg shadow-sm border border-gray-200 hover:bg-gray-50">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Daftar Logbook Belum Selesai</h2>
    </div>

    @if($drafts->isEmpty())
        <div class="bg-white p-10 rounded-2xl shadow-sm border border-gray-100 text-center">
            <p class="text-gray-500">Tidak ada logbook yang tertunda. Semua pekerjaan Anda sudah selesai!</p>
        </div>
    @else
        <div class="grid gap-4">
            @foreach($drafts as $draft)
            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-orange-400 flex justify-between items-center">
                <div>
                    <span class="text-xs font-bold text-orange-500 uppercase tracking-wider">{{ $draft->tim }} - {{ $draft->jenis_gsbpm }}</span>
                    <h3 class="text-lg font-bold text-gray-800 mt-1">{{ $draft->kegiatan }}</h3>
                    <p class="text-sm text-gray-500 mt-1 line-clamp-1">{{ $draft->deskripsi }}</p>
                    <p class="text-xs text-gray-400 mt-2">Tanggal: {{ \Carbon\Carbon::parse($draft->tanggal)->format('d M Y') }}</p>
                </div>
                <a href="{{ route('mahasiswa.logbook.create', ['parent_id' => $draft->id]) }}" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition-colors">
                    Lanjutkan
                </a>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection