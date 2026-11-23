<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIBOOK - Sistem Informasi Buku Magang BPS Kota Surabaya</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-800 bg-gray-50 flex flex-col min-h-screen">

    <!-- Navbar -->
    <header class="bg-white/90 backdrop-blur-md shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <!-- Logo Placeholder -->
                <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">
                    S
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 tracking-tight">SIBOOK-V5</h1>
                    <p class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">BPS Kota Surabaya</p>
                </div>
            </div>
            <nav class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium shadow-sm">
                        Ke Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 font-medium text-sm transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium shadow-sm">
                        Registrasi
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <main class="flex-1">
        <section class="relative bg-white overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute inset-y-0 right-0 w-1/2 bg-indigo-50 rounded-l-full transform translate-x-1/3 -translate-y-1/4 opacity-50"></div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32 relative z-10">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div class="max-w-2xl">
                        <span class="inline-block py-1 px-3 rounded-full bg-indigo-100 text-indigo-700 text-xs font-semibold tracking-wide mb-4">
                            Program Magang Terpadu
                        </span>
                        <h2 class="text-4xl lg:text-5xl font-extrabold text-gray-900 leading-tight mb-6">
                            Mulai Perjalanan Magang Anda di <span class="text-indigo-600">BPS Kota Surabaya</span>
                        </h2>
                        <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                            Kelola administrasi, presensi harian, logbook pekerjaan, dan perizinan magang Anda dalam satu platform digital yang terintegrasi.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            @guest
                                <a href="{{ route('pendaftar.register') }}" class="px-8 py-3.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all font-semibold text-center shadow-lg hover:shadow-xl">
                                    Ajukan Permohonan Sekarang
                                </a>
                            @endguest
                            <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="px-8 py-3.5 bg-white text-indigo-600 border border-indigo-200 rounded-lg hover:bg-indigo-50 transition-all font-semibold text-center">
                                Lihat Status Pendaftaran
                            </a>
                        </div>
                    </div>
                    
                    <!-- Ilustrasi / Gambar -->
                    <div class="hidden lg:block relative">
                    <!-- Efek dekorasi di belakang foto -->
                        <div class="absolute inset-0 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-3xl transform rotate-3 scale-105 opacity-20 blur-lg"></div>
    
                        <!-- Foto Utama -->
                        <img src="{{ asset('images/hero-image.jpeg') }}" 
                            alt="Mahasiswa Magang BPS" 
                            class="relative z-10 w-full max-h-[500px] object-cover rounded-3xl shadow-2xl border-4 border-white">
                    </div>
                </div>
            </div>
        </section>

        <!-- Requirement & Kuota Magang (Bento Grid) -->
        <section id="kuota-magang" class="py-20 bg-gray-50 relative overflow-hidden">
            <!-- Lingkaran pucat dekorasi latar belakang -->
            <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-indigo-100/60 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full bg-violet-100/60 blur-3xl pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                {{-- Section Header --}}
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="inline-block py-1 px-3.5 rounded-full bg-indigo-100 text-indigo-700 text-xs font-semibold tracking-wide mb-3">
                        Informasi Kuota & Ketentuan
                    </span>
                    <h3 class="text-3xl lg:text-4xl font-extrabold text-gray-900 tracking-tight mb-4">
                        Persyaratan & <span class="text-indigo-600">Alokasi Kuota</span> Magang
                    </h3>
                    <p class="text-gray-600 text-base leading-relaxed">
                        Pantau ketersediaan slot magang secara transparan dan pelajari persyaratan lengkap sebelum mengajukan permohonan ke BPS Kota Surabaya.
                    </p>
                </div>

                {{-- Bento Layout --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">

                    {{-- ── 1. Kartu Utama: Total Slot & Status (Bento Hero Card - 2 Col) ── --}}
                    <div class="lg:col-span-2 bg-gradient-to-br from-indigo-600 via-indigo-700 to-violet-800 text-white rounded-3xl p-7 sm:p-9 relative overflow-hidden shadow-xl flex flex-col justify-between hover:shadow-2xl transition-all duration-300 group">
                        {{-- Lingkaran dekorasi dalam kartu --}}
                        <div class="absolute -right-12 -bottom-12 w-64 h-64 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>

                        <div>
                            {{-- Header Kartu: Status Badge & Chip Logo BPS --}}
                            <div class="flex items-start justify-between gap-4">
                                {{-- Badge Status --}}
                                <div>
                                    @if(($requirementData['status'] ?? 'soon') === 'open')
                                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-md border border-white/25 text-white text-xs font-semibold shadow-xs">
                                            <span class="relative flex h-2 w-2">
                                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                              <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                                            </span>
                                            <span>Pendaftaran Dibuka</span>
                                        </div>
                                    @elseif(($requirementData['status'] ?? 'soon') === 'soon')
                                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-400/20 backdrop-blur-md border border-amber-300/30 text-amber-200 text-xs font-semibold shadow-xs">
                                            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                            <span>Segera Dibuka</span>
                                        </div>
                                    @else
                                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-rose-400/20 backdrop-blur-md border border-rose-300/30 text-rose-200 text-xs font-semibold shadow-xs">
                                            <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                                            <span>Pendaftaran Ditutup</span>
                                        </div>
                                    @endif

                                    <h4 class="text-2xl sm:text-3xl font-extrabold tracking-tight mt-4 text-white">
                                        Total Ketersediaan Kuota
                                    </h4>
                                </div>

                                {{-- Logo BPS di Chip Putih Membulat (Sesuai Panduan) --}}
                                <div class="bg-white rounded-full p-2.5 sm:p-3 shadow-md shrink-0 flex items-center justify-center"
                                     title="Badan Pusat Statistik Kota Surabaya">
                                    <x-logo-bps class="h-8 sm:h-10 w-auto object-contain" />
                                </div>
                            </div>

                            {{-- Highlight Angka Sisa Slot ("19 dari 45 slot") --}}
                            <div class="mt-8">
                                <p class="text-xs uppercase tracking-widest text-indigo-200 font-semibold mb-1">
                                    Sisa Slot Terbuka
                                </p>
                                <div class="flex items-baseline gap-2.5 flex-wrap">
                                    <span class="text-5xl sm:text-6xl font-extrabold tracking-tight text-white count-up-number"
                                          data-target="{{ $requirementData['total_remaining'] ?? 0 }}">
                                        0
                                    </span>
                                    <span class="text-xl sm:text-2xl text-indigo-200 font-medium">
                                        dari <span class="font-bold text-white">{{ $requirementData['total_quota'] ?? 0 }}</span> slot tersedia
                                    </span>
                                </div>

                                {{-- Status Note --}}
                                @if(!empty($requirementData['status_note']))
                                    <div class="mt-4 inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white/10 backdrop-blur-sm border border-white/15 text-xs text-indigo-100 leading-relaxed">
                                        <svg class="w-4 h-4 text-amber-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>{{ $requirementData['status_note'] }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Progress Bar Total Slot --}}
                        <div class="mt-8 pt-6 border-t border-white/15">
                            <div class="flex items-center justify-between text-xs text-indigo-200 font-medium mb-2">
                                <span>Mahasiswa Magang Aktif: <strong class="text-white">{{ $requirementData['total_active'] ?? 0 }} orang</strong></span>
                                <span>{{ $requirementData['total_percentage'] ?? 0 }}% terisi</span>
                            </div>
                            <div class="w-full bg-white/20 h-3 rounded-full overflow-hidden p-0.5 backdrop-blur-sm">
                                <div class="bg-white h-full rounded-full transition-all duration-1000 ease-out progress-bar-animated"
                                     data-width="{{ $requirementData['total_percentage'] ?? 0 }}%"
                                     style="width: 0%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- ── 2. Kartu Persyaratan: Checklist (Bento Card - 1 Col) ── --}}
                    <div class="bg-white rounded-3xl p-7 border border-gray-150 shadow-sm flex flex-col justify-between hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                        <div>
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-extrabold text-gray-900 tracking-tight">Persyaratan</h4>
                                    <p class="text-xs text-gray-400">Kriteria pendaftaran magang</p>
                                </div>
                            </div>

                            {{-- Checklist Items --}}
                            <ul class="space-y-3">
                                @forelse($requirementData['requirements'] ?? [] as $req)
                                    <li class="flex items-start gap-2.5 text-xs text-gray-700 leading-relaxed">
                                        <div class="w-5 h-5 rounded-md bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 mt-0.5">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                        <span>{{ $req }}</span>
                                    </li>
                                @empty
                                    <li class="text-xs text-gray-400 italic">Belum ada persyaratan yang ditentukan.</li>
                                @endforelse
                            </ul>
                        </div>

                        {{-- Catatan Persyaratan Tambahan --}}
                        @if(!empty($requirementData['requirements_note']))
                            <div class="mt-6 pt-4 border-t border-gray-100">
                                <p class="text-[11px] text-gray-500 leading-relaxed italic bg-gray-50 p-3 rounded-xl border border-gray-100">
                                    💡 {{ $requirementData['requirements_note'] }}
                                </p>
                            </div>
                        @endif
                    </div>

                    {{-- ── 3. Kartu Kelompok Jurusan (Bento Cards per Kelompok) ── --}}
                    @forelse($requirementData['groups'] ?? [] as $group)
                        @php
                            $grp = is_array($group) ? (object) $group : (is_object($group) ? $group : null);
                            if (!$grp) continue;

                            $quota = (int) ($grp->quota ?? 0);
                            $active = (int) ($grp->active ?? 0);
                            $rem = isset($grp->remaining) ? (int) $grp->remaining : max(0, $quota - $active);
                            $remPct = $quota > 0 ? round(($rem / $quota) * 100, 1) : 0;
                            $pct = isset($grp->percentage) ? (int) $grp->percentage : ($quota > 0 ? min(100, round(($active / $quota) * 100)) : 0);
                            $statusColor = $grp->status_color ?? ($rem <= 0 ? 'red' : ($remPct <= 30 ? 'yellow' : 'green'));

                            $badgeClass = match($statusColor) {
                                'red'    => 'bg-rose-50 text-rose-700 border-rose-200',
                                'yellow' => 'bg-amber-50 text-amber-700 border-amber-200',
                                default  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            };

                            $barClass = match($statusColor) {
                                'red'    => 'bg-rose-500',
                                'yellow' => 'bg-amber-500',
                                default  => 'bg-emerald-500',
                            };

                            $majors = is_array($grp->majors ?? null) ? $grp->majors : ($grp->majors ?? []);
                        @endphp
                        <div class="bg-white rounded-3xl p-6 sm:p-7 border border-gray-150 shadow-sm flex flex-col justify-between hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                            <div>
                                {{-- Header Kelompok: Nama & Badge Sisa --}}
                                <div class="flex items-start justify-between gap-3 mb-4">
                                    <div>
                                        <span class="text-[10.5px] font-bold uppercase tracking-wider text-indigo-600 block mb-1">
                                            {{ !empty($grp->is_catch_all) ? 'Kelompok Umum' : 'Kelompok Jurusan' }}
                                        </span>
                                        <h4 class="text-lg font-extrabold text-gray-900 tracking-tight leading-snug">
                                            {{ $grp->name ?? 'Kelompok Jurusan' }}
                                        </h4>
                                    </div>

                                    {{-- Badge Kuota (Warna + Teks Aksesibel) --}}
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold border shrink-0 {{ $badgeClass }}">
                                        @if($rem <= 0)
                                            <span class="inline-flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                                Penuh
                                            </span>
                                        @elseif($remPct <= 30)
                                            <span class="inline-flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                                Tersisa {{ $rem }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                Tersedia ({{ $rem }})
                                            </span>
                                        @endif
                                    </span>
                                </div>

                                {{-- Angka Sisa Kuota Besar --}}
                                <div class="mt-4 flex items-baseline gap-2">
                                    <span class="text-4xl font-extrabold text-gray-900 count-up-number"
                                          data-target="{{ $rem }}">
                                        0
                                    </span>
                                    <span class="text-xs text-gray-500 font-medium">
                                        slot tersisa dari <strong class="text-gray-800">{{ $quota }}</strong> total kuota
                                    </span>
                                </div>

                                {{-- Progress Bar Terisi Halus --}}
                                <div class="w-full bg-gray-100 h-2 rounded-full mt-3 overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-1000 ease-out progress-bar-animated {{ $barClass }}"
                                         data-width="{{ $pct }}%"
                                         style="width: 0%"></div>
                                </div>

                                {{-- Deretan Titik Slot (Slot Dots Visualization) --}}
                                <div class="flex items-center gap-1 mt-2.5 flex-wrap">
                                    @php
                                        // Batasi max 20 titik visual agar rapi di layar
                                        $displayDots = min(20, $quota);
                                        $activeDots = $quota > 0 ? round(($active / $quota) * $displayDots) : 0;
                                    @endphp
                                    @for($i = 0; $i < $displayDots; $i++)
                                        <span class="w-1.5 h-1.5 rounded-full {{ $i < $activeDots ? 'bg-gray-300' : 'bg-indigo-600' }}"
                                              title="{{ $i < $activeDots ? 'Terisi' : 'Tersedia' }}"></span>
                                    @endfor
                                    @if($quota > 20)
                                        <span class="text-[9px] text-gray-400 font-mono">+{{ $quota - 20 }}</span>
                                    @endif
                                </div>

                                {{-- Chip Jurusan yang Tercakup --}}
                                <div class="mt-5 pt-4 border-t border-gray-100">
                                    <p class="text-[11px] font-semibold text-gray-400 mb-2">Program Studi / Jurusan Terkait:</p>
                                    <div class="flex flex-wrap gap-1.5">
                                        @forelse($majors as $major)
                                            @php
                                                $majorName = is_array($major) ? ($major['name'] ?? '') : ($major->name ?? (string) $major);
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-indigo-50/80 text-indigo-700 text-xs font-medium border border-indigo-100">
                                                {{ $majorName }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-gray-400 italic">Mencakup seluruh jurusan lainnya.</span>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            {{-- Footer Kartu --}}
                            <div class="mt-5 pt-3 border-t border-gray-100/60 flex items-center justify-between text-[11px] text-gray-400">
                                <span>Terisi: {{ $active }} mahasiswa</span>
                                <span>{{ $pct }}% kuota</span>
                            </div>
                        </div>
                    @empty
                        <div class="lg:col-span-3 bg-white rounded-3xl p-8 border border-gray-150 text-center shadow-xs">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 mx-auto flex items-center justify-center mb-3">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                            <h4 class="text-sm font-bold text-gray-800">Alokasi Kuota Jurusan Sedang Disiapkan</h4>
                            <p class="text-xs text-gray-500 mt-1 max-w-md mx-auto">
                                Rincian pembagian kuota per kelompok jurusan saat ini sedang disusun oleh administrator. Informasi akan segera diperbarui.
                            </p>
                        </div>
                    @endforelse

                </div>

                {{-- Timestamp Terakhir Diperbarui --}}
                <div class="mt-8 text-center text-xs text-gray-400">
                    Informasi kuota terakhir diperbarui pada:
                    <span class="font-semibold text-gray-600">
                        @php
                            $lu = $requirementData['last_updated'] ?? null;
                            $formattedDate = $lu instanceof \Carbon\Carbon
                                ? $lu->translatedFormat('d F Y, H:i')
                                : (\Carbon\Carbon::parse($lu ?? now())->translatedFormat('d F Y, H:i'));
                        @endphp
                        {{ $formattedDate }} WIB
                    </span>
                </div>
            </div>
        </section>

        <!-- Alur Pendaftaran -->
        <section id="alur" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">Alur Pengajuan Magang</h3>
                    <p class="text-gray-600">Ikuti langkah-langkah berikut untuk mendaftar sebagai mahasiswa magang di BPS Kota Surabaya.</p>
                </div>

                <div class="grid md:grid-cols-4 gap-8">
                    <!-- Step 1 -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 relative hover:-translate-y-1 transition-transform">
                        <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center text-xl font-bold mb-4">1</div>
                        <h4 class="font-bold text-gray-900 mb-2">Registrasi Akun</h4>
                        <p class="text-sm text-gray-500 leading-relaxed">Buat akun dan verifikasi email Anda melalui tautan yang dikirimkan.</p>
                    </div>
                    <!-- Step 2 -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 relative hover:-translate-y-1 transition-transform">
                        <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center text-xl font-bold mb-4">2</div>
                        <h4 class="font-bold text-gray-900 mb-2">Upload Dokumen</h4>
                        <p class="text-sm text-gray-500 leading-relaxed">Lengkapi berkas seperti surat permohonan kampus, CV, dan transkrip nilai.</p>
                    </div>
                    <!-- Step 3 -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 relative hover:-translate-y-1 transition-transform">
                        <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center text-xl font-bold mb-4">3</div>
                        <h4 class="font-bold text-gray-900 mb-2">Review Admin</h4>
                        <p class="text-sm text-gray-500 leading-relaxed">Admin akan memverifikasi kelengkapan dokumen yang Anda unggah.</p>
                    </div>
                    <!-- Step 4 -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 relative hover:-translate-y-1 transition-transform">
                        <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center text-xl font-bold mb-4">4</div>
                        <h4 class="font-bold text-gray-900 mb-2">Persetujuan</h4>
                        <p class="text-sm text-gray-500 leading-relaxed">Super User memberikan persetujuan akhir dan akun Anda berubah menjadi Mahasiswa Magang.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Fitur Section -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">Fitur Setelah Diterima</h3>
                    <p class="text-gray-600">Kemudahan administrasi selama masa magang Anda.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Fitur 1 -->
                    <div class="p-8 bg-gray-50 rounded-2xl border border-gray-100">
                        <div class="w-14 h-14 bg-indigo-600 text-white rounded-xl flex items-center justify-center mb-6 shadow-md">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3">Presensi Geolocation</h4>
                        <p class="text-gray-600 leading-relaxed text-sm">Lakukan presensi masuk dan pulang langsung dari ponsel Anda dengan validasi radius kantor (1 km).</p>
                    </div>
                    <!-- Fitur 2 -->
                    <div class="p-8 bg-gray-50 rounded-2xl border border-gray-100">
                        <div class="w-14 h-14 bg-indigo-600 text-white rounded-xl flex items-center justify-center mb-6 shadow-md">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3">Logbook Harian</h4>
                        <p class="text-gray-600 leading-relaxed text-sm">Catat kegiatan harian Anda sebelum melakukan presensi pulang. Terhubung langsung dengan Admin.</p>
                    </div>
                    <!-- Fitur 3 -->
                    <div class="p-8 bg-gray-50 rounded-2xl border border-gray-100">
                        <div class="w-14 h-14 bg-indigo-600 text-white rounded-xl flex items-center justify-center mb-6 shadow-md">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3">Perizinan Online</h4>
                        <p class="text-gray-600 leading-relaxed text-sm">Ajukan izin tidak masuk dengan mudah. Cukup unggah link Google Drive bukti surat izin Anda.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-indigo-500 rounded flex items-center justify-center text-white font-bold text-sm">
                    S
                </div>
                <span class="text-lg font-bold text-white tracking-tight">SIBOOK-V5</span>
            </div>
            <p class="text-sm text-center md:text-left text-gray-400">
                &copy; {{ date('Y') }} Badan Pusat Statistik Kota Surabaya.<br class="md:hidden"> Sistem Informasi Buku Magang.
            </p>
            <div class="flex gap-4 text-sm">
                <a href="https://surabayakota.bps.go.id" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors">Portal BPS Kota Surabaya</a>
                <a href="mailto:bps3578@bps.go.id" class="hover:text-white transition-colors">Bantuan & Kontak</a>
            </div>
        </div>
    </footer>

    {{-- Script Animasi Count-Up & Progress Bar (IntersectionObserver) --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            // 1. Observer untuk Progress Bars
            const progressBars = document.querySelectorAll('.progress-bar-animated');
            if (progressBars.length > 0) {
                const progressObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const bar = entry.target;
                            const targetWidth = bar.getAttribute('data-width') || '0%';
                            bar.style.width = targetWidth;
                            observer.unobserve(bar);
                        }
                    });
                }, { threshold: 0.15 });

                progressBars.forEach(bar => progressObserver.observe(bar));
            }

            // 2. Observer untuk Count-up Numbers
            const countElements = document.querySelectorAll('.count-up-number');
            if (countElements.length > 0) {
                const countObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const el = entry.target;
                            const target = parseInt(el.getAttribute('data-target'), 10) || 0;

                            if (prefersReducedMotion || target <= 0) {
                                el.textContent = target;
                            } else {
                                animateCountUp(el, target, 1200);
                            }
                            observer.unobserve(el);
                        }
                    });
                }, { threshold: 0.15 });

                countElements.forEach(el => countObserver.observe(el));
            }

            function animateCountUp(element, endVal, duration) {
                let startTimestamp = null;
                const startVal = 0;

                const step = (timestamp) => {
                    if (!startTimestamp) startTimestamp = timestamp;
                    const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                    // Easing cubic out
                    const easeOutProgress = 1 - Math.pow(1 - progress, 3);
                    element.textContent = Math.floor(easeOutProgress * (endVal - startVal) + startVal);
                    if (progress < 1) {
                        window.requestAnimationFrame(step);
                    } else {
                        element.textContent = endVal;
                    }
                };

                window.requestAnimationFrame(step);
            }
        });
    </script>
</body>
</html>