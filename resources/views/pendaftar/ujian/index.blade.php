@extends('layouts.role', ['role' => 'pendaftar'])

@section('title', 'Ujian Kompetensi')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Ujian Kompetensi</h2>
        <p class="text-gray-500 mt-1">Selesaikan ketiga tahapan ujian di bawah ini untuk melanjutkan proses magang.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- 1. Ujian Esai -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
            <div class="p-6 flex-1">
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Ujian Esai</h3>
                <p class="text-sm text-gray-500 mb-4 leading-relaxed">Ujian awal untuk mengukur kemampuan berpikir kritis. Apakah Anda layak melanjutkan magang?</p>
                
                <div class="mb-4">
                    @if($permohonan->status_ujian_esai === 'lulus')
                        <span class="inline-flex items-center text-green-600 font-bold text-sm"><svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Lulus</span>
                    @elseif($permohonan->status_ujian_esai === 'gagal')
                        <span class="inline-flex items-center text-red-600 font-bold text-sm"><svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Gagal</span>
                    @else
                        <span class="inline-flex items-center text-gray-500 font-bold text-sm">Belum Dikerjakan</span>
                    @endif
                </div>
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-100">
                <a href="{{ $gformEsai }}" target="_blank" class="block w-full text-center px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors">Kerjakan Ujian</a>
            </div>
        </div>

        <!-- 2. Ujian Tulis -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
            <div class="p-6 flex-1">
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Ujian Tulis</h3>
                <p class="text-sm text-gray-500 mb-4 leading-relaxed">Ujian tulis untuk mengukur pemahaman dasar terkait bidang yang akan digeluti.</p>
                
                <div class="mb-4">
                    @if($permohonan->status_ujian_tulis === 'lulus')
                        <span class="inline-flex items-center text-green-600 font-bold text-sm"><svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Lulus</span>
                    @elseif($permohonan->status_ujian_tulis === 'gagal')
                        <span class="inline-flex items-center text-red-600 font-bold text-sm"><svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Gagal</span>
                    @else
                        <span class="inline-flex items-center text-gray-500 font-bold text-sm">Belum Dikerjakan</span>
                    @endif
                </div>
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-100">
                <a href="{{ $gformTulis }}" target="_blank" class="block w-full text-center px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors">Kerjakan Ujian</a>
            </div>
        </div>

        <!-- 3. Interview -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
            <div class="p-6 flex-1">
                <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Interview</h3>
                <p class="text-sm text-gray-500 mb-4 leading-relaxed">Tahap wawancara akhir. Silakan hubungi kontak di bawah ini untuk menjadwalkan interview.</p>
                
                <div class="mb-4">
                    @if($permohonan->status_interview === 'lulus')
                        <span class="inline-flex items-center text-green-600 font-bold text-sm"><svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Lulus</span>
                    @elseif($permohonan->status_interview === 'gagal')
                        <span class="inline-flex items-center text-red-600 font-bold text-sm"><svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Gagal</span>
                    @else
                        <span class="inline-flex items-center text-gray-500 font-bold text-sm">Menunggu Jadwal</span>
                    @endif
                </div>
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-100 text-xs text-gray-600 space-y-1">
                <p class="font-bold text-gray-800 mb-2">Hubungi Admin:</p>
                <a href="https://wa.me/6212345678" target="_blank" class="block hover:text-indigo-600">📞 +62 12345678</a>
                <a href="https://wa.me/6212345678" target="_blank" class="block hover:text-indigo-600">📞 +62 12345678</a>
                <a href="https://wa.me/6212345678" target="_blank" class="block hover:text-indigo-600">📞 +62 12345678</a>
            </div>
        </div>
    </div>
</div>
@endsection