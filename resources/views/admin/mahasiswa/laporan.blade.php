@extends('layouts.role', ['role' => 'admin'])

@section('title', 'Laporan Magang - ' . $user->name)

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Breadcrumbs & Header Aksi --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 print:hidden">
        <div>
            <nav class="flex items-center gap-2 text-xs text-gray-500 font-medium">
                <a href="{{ route('admin.mahasiswa.index') }}" class="hover:text-gray-800">Mahasiswa Magang</a>
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-400">Laporan & Rekapitulasi</span>
            </nav>
            <h1 class="text-2xl lg:text-3xl font-extrabold text-gray-900 tracking-tight mt-1">
                Laporan Hasil Rekap Mahasiswa
            </h1>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-2.5">
            {{-- Tombol Export Excel --}}
            <a href="{{ route('admin.mahasiswa.laporan.export', ['user' => $user->id, 'bulan' => request('bulan')]) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span>Unduh Excel (.xlsx)</span>
            </a>

            {{-- Tombol Cetak / PDF --}}
            <button type="button" onclick="window.print()" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 rounded-xl text-xs font-semibold shadow-sm transition">
                <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak / PDF</span>
            </button>
        </div>
    </div>

    {{-- KOP SURAT / HEADER CETAK KHUSUS PRINT --}}
    <div class="hidden print:block border-b-2 border-gray-900 pb-4 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="font-extrabold text-2xl text-blue-900 tracking-wider">BPS KOTA SURABAYA</div>
                <div class="text-xs text-gray-600 border-l border-gray-400 pl-4">
                    <p class="font-semibold text-gray-900">BADAN PUSAT STATISTIK KOTA SURABAYA</p>
                    <p>Sistem Informasi Manajemen Magang (SiBook)</p>
                    <p class="text-[10px]">Jl. Raya Kendangsari No. 40 - 42, Surabaya, Jawa Timur</p>
                </div>
            </div>
            <div class="text-right text-xs text-gray-500">
                <p class="font-semibold text-gray-800">REKAPITULASI MAGANG</p>
                <p>Tanggal: {{ now()->translatedFormat('d F Y') }}</p>
            </div>
        </div>
    </div>

    {{-- CARD PROFIL MAHASISWA --}}
    @php
        $profile = $user->mahasiswaProfile;
        $magang = $user->magang;
    @endphp
    <div class="bg-white rounded-2xl border border-gray-150 p-6 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-start gap-4">
                {{-- Avatar Initial --}}
                @php
                    $words = preg_split('/\s+/', trim($user->name));
                    $initials = '';
                    foreach (array_slice($words, 0, 2) as $w) {
                        $initials .= strtoupper(mb_substr($w, 0, 1));
                    }
                @endphp
                <div class="w-14 h-14 rounded-2xl bg-[#4338CA] text-white font-bold text-lg flex items-center justify-center flex-shrink-0 shadow-sm">
                    {{ $initials ?: 'M' }}
                </div>
                <div>
                    <div class="flex items-center gap-2.5">
                        <h2 class="text-lg font-bold text-gray-900">{{ $user->name }}</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                            {{ ucfirst($magang->status ?? 'Aktif') }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">
                        <span class="font-mono text-gray-700 font-semibold">{{ $profile->nim ?? '-' }}</span> • 
                        {{ $profile->universitas ?? '-' }} • 
                        {{ $profile->program_studi ?? ($profile->jurusan ?? '-') }}
                    </p>
                    <div class="flex flex-wrap items-center gap-4 mt-2.5 text-xs text-gray-600">
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ $user->email }}
                        </span>
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{ $profile->no_hp ?? '-' }}
                        </span>
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Periode: 
                            @if($magang)
                                {{ \Carbon\Carbon::parse($magang->tanggal_mulai)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($magang->tanggal_selesai)->format('d M Y') }}
                            @else
                                Belum diset
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            {{-- Filter Bulan --}}
            <div class="print:hidden flex items-center gap-2 self-start md:self-auto bg-gray-50 p-2 rounded-xl border border-gray-150">
                <form method="GET" action="{{ route('admin.mahasiswa.laporan', $user->id) }}" class="flex items-center gap-2">
                    <span class="text-xs text-gray-500 font-medium">Periode Bulan:</span>
                    <input type="month" name="bulan" value="{{ request('bulan') }}"
                           onchange="this.form.submit()"
                           class="text-xs bg-white border border-gray-200 rounded-lg p-1.5 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    @if(request('bulan'))
                        <a href="{{ route('admin.mahasiswa.laporan', $user->id) }}" class="text-xs text-gray-400 hover:text-red-600 font-semibold">Semua</a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    {{-- STATISTIK KEHADIRAN (HADIR, IZIN, ALPHA SESUAI DIAGRAM) --}}
    <div>
        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">Statistik Kehadiran</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            
            {{-- Hadir --}}
            <div class="bg-white rounded-2xl border border-emerald-100 p-4 shadow-sm relative overflow-hidden">
                <div class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider">Hadir Tepat Waktu</div>
                <div class="text-2xl lg:text-3xl font-extrabold text-emerald-700 mt-1.5">{{ $stats['hadir'] }}</div>
                <div class="text-[11px] text-gray-400 mt-1">Hari kerja</div>
            </div>

            {{-- Terlambat --}}
            <div class="bg-white rounded-2xl border border-amber-100 p-4 shadow-sm relative overflow-hidden">
                <div class="text-[11px] font-bold text-amber-600 uppercase tracking-wider">Terlambat</div>
                <div class="text-2xl lg:text-3xl font-extrabold text-amber-700 mt-1.5">{{ $stats['terlambat'] }}</div>
                <div class="text-[11px] text-gray-400 mt-1">Hari kerja</div>
            </div>

            {{-- Izin / Sakit --}}
            <div class="bg-white rounded-2xl border border-blue-100 p-4 shadow-sm relative overflow-hidden">
                <div class="text-[11px] font-bold text-blue-600 uppercase tracking-wider">Izin & Sakit</div>
                <div class="text-2xl lg:text-3xl font-extrabold text-blue-700 mt-1.5">{{ $stats['izin'] + $stats['sakit'] }}</div>
                <div class="text-[11px] text-gray-400 mt-1">Hari pengajuan</div>
            </div>

            {{-- Alpha --}}
            <div class="bg-white rounded-2xl border border-rose-100 p-4 shadow-sm relative overflow-hidden">
                <div class="text-[11px] font-bold text-rose-600 uppercase tracking-wider">Alpha (Tanpa Ket.)</div>
                <div class="text-2xl lg:text-3xl font-extrabold text-rose-700 mt-1.5">{{ $stats['alpha'] }}</div>
                <div class="text-[11px] text-gray-400 mt-1">Hari</div>
            </div>

            {{-- Total Presensi --}}
            <div class="bg-white rounded-2xl border border-indigo-100 p-4 shadow-sm relative overflow-hidden">
                <div class="text-[11px] font-bold text-indigo-600 uppercase tracking-wider">Total Hari Tercatat</div>
                <div class="text-2xl lg:text-3xl font-extrabold text-indigo-700 mt-1.5">{{ $stats['total_presensi'] }}</div>
                <div class="text-[11px] text-gray-400 mt-1">Tingkat: <span class="font-bold text-gray-700">{{ $stats['persentase_kehadiran'] }}%</span></div>
            </div>

            {{-- Total Logbook --}}
            <div class="bg-white rounded-2xl border border-orange-100 p-4 shadow-sm relative overflow-hidden">
                <div class="text-[11px] font-bold text-orange-600 uppercase tracking-wider">Total Logbook</div>
                <div class="text-2xl lg:text-3xl font-extrabold text-orange-700 mt-1.5">{{ $stats['total_logbook'] }}</div>
                <div class="text-[11px] text-gray-400 mt-1">Selesai: <span class="font-bold text-gray-700">{{ $stats['logbook_approved'] }}</span></div>
            </div>

        </div>
    </div>

    {{-- TABEL 1: REKAP PRESENSI HARIAN --}}
    <div class="bg-white rounded-2xl border border-gray-150 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-900">Riwayat Presensi Harian</h3>
                <p class="text-xs text-gray-500">Catatan jam masuk, kepulangan, dan verifikasi lokasi GPS.</p>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 bg-gray-100 text-gray-700 rounded-lg">
                {{ $presensis->count() }} Data Presensi
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-[13px]">
                <thead>
                    <tr class="border-b border-gray-100 font-semibold text-gray-700 bg-gray-50/50">
                        <th class="py-3.5 pl-6 pr-3">No</th>
                        <th class="py-3.5 px-3">Tanggal</th>
                        <th class="py-3.5 px-3">Jam Masuk</th>
                        <th class="py-3.5 px-3">Jam Pulang</th>
                        <th class="py-3.5 px-3">Tipe</th>
                        <th class="py-3.5 px-3">Jarak Masuk</th>
                        <th class="py-3.5 pl-3 pr-6 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-800">
                    @forelse($presensis as $idx => $p)
                        @php
                            $status = $p->status?->value ?? (string) $p->status;
                            $badge = match($status) {
                                'hadir' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'terlambat' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'izin' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'sakit' => 'bg-purple-50 text-purple-700 border-purple-200',
                                default => 'bg-rose-50 text-rose-700 border-rose-200',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <td class="py-3.5 pl-6 pr-3 text-gray-400 font-mono text-xs">{{ $idx + 1 }}</td>
                            <td class="py-3.5 px-3 font-semibold text-gray-900">
                                {{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d F Y') }}
                            </td>
                            <td class="py-3.5 px-3 font-mono text-xs">
                                {{ $p->jam_masuk ? substr($p->jam_masuk, 0, 5) : '-' }}
                            </td>
                            <td class="py-3.5 px-3 font-mono text-xs">
                                {{ $p->jam_pulang ? substr($p->jam_pulang, 0, 5) : '-' }}
                            </td>
                            <td class="py-3.5 px-3 capitalize text-xs text-gray-600">
                                {{ str_replace('_', ' ', $p->tipe_presensi ?? 'kantor') }}
                            </td>
                            <td class="py-3.5 px-3 font-mono text-xs text-gray-500">
                                {{ $p->jarak_masuk_m ? round($p->jarak_masuk_m) . ' m' : '-' }}
                            </td>
                            <td class="py-3.5 pl-3 pr-6 text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $badge }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-gray-400">
                                Belum ada riwayat presensi tercatat untuk mahasiswa ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- TABEL 2: REKAP LOGBOOK KEGIATAN --}}
    <div class="bg-white rounded-2xl border border-gray-150 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-900">Riwayat Logbook Kegiatan</h3>
                <p class="text-xs text-gray-500">Aktivitas pekerjaan harian mahasiswa dan status verifikasi.</p>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 bg-gray-100 text-gray-700 rounded-lg">
                {{ $logbooks->count() }} Kegiatan
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-[13px]">
                <thead>
                    <tr class="border-b border-gray-100 font-semibold text-gray-700 bg-gray-50/50">
                        <th class="py-3.5 pl-6 pr-3">No</th>
                        <th class="py-3.5 px-3">Tanggal</th>
                        <th class="py-3.5 px-3">Divisi / Tim</th>
                        <th class="py-3.5 px-3">Judul & Deskripsi Kegiatan</th>
                        <th class="py-3.5 px-3">Jam Kerja</th>
                        <th class="py-3.5 px-3">Bukti</th>
                        <th class="py-3.5 pl-3 pr-6 text-right">Status Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-800">
                    @forelse($logbooks as $idx => $lb)
                        @php
                            $lbStatus = $lb->status?->value ?? (string) $lb->status;
                            $lbBadge = match($lbStatus) {
                                'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'submitted' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                                default => 'bg-gray-50 text-gray-600 border-gray-200',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <td class="py-3.5 pl-6 pr-3 text-gray-400 font-mono text-xs">{{ $idx + 1 }}</td>
                            <td class="py-3.5 px-3 whitespace-nowrap font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($lb->tanggal)->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-3.5 px-3 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                    {{ $lb->tim ?? 'Kegiatan Lainnya' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-3 max-w-md">
                                <div class="font-semibold text-gray-900">{{ $lb->kegiatan ?: '-' }}</div>
                                <div class="text-xs text-gray-500 line-clamp-2 mt-0.5">{{ $lb->deskripsi ?: '-' }}</div>
                                @if($lb->catatan_verifikator)
                                    <div class="text-[11px] text-orange-700 bg-orange-50/70 p-1.5 rounded mt-1 border border-orange-200/50">
                                        <span class="font-semibold">Catatan:</span> {{ $lb->catatan_verifikator }}
                                    </div>
                                @endif
                            </td>
                            <td class="py-3.5 px-3 font-mono text-xs whitespace-nowrap">
                                {{ ($lb->jam_mulai ? substr($lb->jam_mulai, 0, 5) : '-') . ' - ' . ($lb->jam_selesai ? substr($lb->jam_selesai, 0, 5) : '-') }}
                            </td>
                            <td class="py-3.5 px-3 whitespace-nowrap">
                                @if($lb->link_bukti)
                                    <a href="{{ $lb->link_bukti }}" target="_blank" class="text-xs font-semibold text-orange-600 hover:text-orange-700 inline-flex items-center gap-1">
                                        <span>Bukti</span>
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 pl-3 pr-6 text-right whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $lbBadge }}">
                                    {{ ucfirst($lbStatus) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-gray-400">
                                Belum ada catatan logbook kegiatan untuk mahasiswa ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- FOOTER KHUSUS CETAK PRINT / TANDA TANGAN --}}
    <div class="hidden print:block pt-10">
        <div class="flex justify-between items-start text-xs text-gray-800">
            <div class="text-center w-64">
                <p>Mengetahui,</p>
                <p class="font-semibold">Pembimbing Lapangan / Verifikator</p>
                <div class="h-20"></div>
                <p class="font-bold underline">___________________________</p>
                <p class="text-gray-500">NIP. .................................................</p>
            </div>

            <div class="text-center w-64">
                <p>Surabaya, {{ now()->translatedFormat('d F Y') }}</p>
                <p class="font-semibold">Mahasiswa Magang,</p>
                <div class="h-20"></div>
                <p class="font-bold underline">{{ $user->name }}</p>
                <p class="text-gray-500">NIM. {{ $profile->nim ?? '.....................................' }}</p>
            </div>
        </div>
    </div>

</div>
@endsection
