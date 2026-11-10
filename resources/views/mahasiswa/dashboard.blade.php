@extends('layouts.role', ['role' => 'mahasiswa'])

@section('title', 'Dashboard Mahasiswa')

@section('content')
    <!-- Kartu Sambutan & Info Magang -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-1">Halo, {{ $user->name }}! 👋</h2>
        <p class="text-gray-500 text-sm mb-4">Selamat datang di Sistem Informasi Buku Magang BPS Kota Surabaya.</p>

        @if($magang)
            <div class="flex flex-wrap gap-6 mt-4">
                <div>
                    <span class="block text-xs text-gray-400 uppercase tracking-wider">Periode Magang</span>
                    <span class="font-semibold text-gray-700">
                        {{ $magang->tanggal_mulai->format('d M Y') }} - {{ $magang->tanggal_selesai->format('d M Y') }}
                    </span>
                </div>
                <div>
                    <span class="block text-xs text-gray-400 uppercase tracking-wider">Status</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ $magang->status->label() }}
                    </span>
                </div>
            </div>
        @else
            <div class="p-4 bg-yellow-50 text-yellow-800 rounded-lg mt-4">
                Data magang tidak ditemukan. Hubungi admin.
            </div>
        @endif
    </div>

    <!-- Tombol Cepat -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Presensi -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
            <div>
                <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="font-bold text-gray-800 mb-1">Presensi Hari Ini</h3>
                <p class="text-sm text-gray-500 mb-4">
                    @if($presensiHariIni)
                        Masuk: <span class="font-semibold">{{ $presensiHariIni->jam_masuk ?? '-' }}</span> | Pulang: <span class="font-semibold">{{ $presensiHariIni->jam_pulang ?? '-' }}</span>
                    @else
                        Anda belum melakukan presensi masuk hari ini.
                    @endif
                </p>
            </div>
            <a href="#" class="w-full text-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium">Buka Presensi</a>
        </div>

        <!-- Logbook -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
            <div>
                <div class="w-10 h-10 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <h3 class="font-bold text-gray-800 mb-1">Logbook Harian</h3>
                <p class="text-sm text-gray-500 mb-4">Isi logbook hari ini sebelum Anda melakukan presensi pulang.</p>
            </div>
            <a href="#" class="w-full text-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium">Isi Logbook</a>
        </div>

        <!-- Perizinan -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
            <div>
                <div class="w-10 h-10 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="font-bold text-gray-800 mb-1">Perizinan</h3>
                <p class="text-sm text-gray-500 mb-4">Ajukan izin jika Anda berhalangan hadir dengan menyertakan bukti.</p>
            </div>
            <a href="#" class="w-full text-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 text-sm font-medium">Ajukan Izin</a>
        </div>
    </div>
@endsection