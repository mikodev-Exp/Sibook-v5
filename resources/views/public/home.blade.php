<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIBOOK - Sistem Informasi Buku Magang BPS Kota Surabaya</title>

    <!-- Typography: Figtree & Space Mono untuk estetika galeri seni & kuratorial -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800|space-mono:400,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Figtree', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .font-mono-tag {
            font-family: 'Space Mono', ui-monospace, monospace;
        }
        .gallery-gradient-border {
            position: relative;
            background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.02));
        }
        .gallery-gradient-border::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(135deg, rgba(255,255,255,0.2), rgba(255,255,255,0.05));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }
    </style>
</head>
<body class="bg-[#FAFAFA] text-[#09090B] flex flex-col min-h-screen antialiased selection:bg-zinc-900 selection:text-white">

    <!-- Top Navigation (Gallery Header) -->
    <header class="bg-white/80 backdrop-blur-md border-b border-zinc-200/80 sticky top-0 z-50 transition-all">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 py-3.5 flex justify-between items-center">
            {{-- Brand Logo & Curator Desk --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-xl bg-zinc-950 text-white flex items-center justify-center font-bold text-sm tracking-tighter shadow-sm group-hover:scale-105 transition-transform">
                    SB
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-lg font-extrabold tracking-tight text-zinc-950">SIBOOK</span>
                        <span class="text-[10px] font-mono-tag px-1.5 py-0.5 rounded bg-zinc-100 text-zinc-600 border border-zinc-200/80 font-bold">V5.0</span>
                    </div>
                    <p class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-widest leading-none">BPS Kota Surabaya</p>
                </div>
            </a>

            {{-- Nav Links --}}
            <div class="hidden md:flex items-center gap-8 text-xs font-medium text-zinc-500">
                <a href="#kuota-magang" class="hover:text-zinc-950 transition-colors">Koleksi Kuota</a>
                <a href="#alur" class="hover:text-zinc-950 transition-colors">Alur Kurasi</a>
                <a href="#fitur" class="hover:text-zinc-950 transition-colors">Fitur Studio</a>
            </div>

            {{-- Auth Call-to-Action --}}
            <nav class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-zinc-950 text-white hover:bg-zinc-800 transition-colors text-xs font-medium shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>Masuk Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                       class="px-4 py-2 text-zinc-600 hover:text-zinc-950 font-medium text-xs transition-colors">
                        Login
                    </a>
                    <a href="{{ route('pendaftar.register') }}" 
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-zinc-950 text-white hover:bg-zinc-800 transition-all text-xs font-medium shadow-sm hover:shadow">
                        <span>Ajukan Magang</span>
                        <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="flex-1">
        <!-- Hero Section (Manifesto & Exhibition Intro) -->
        <section class="relative bg-white border-b border-zinc-200/80 overflow-hidden py-20 lg:py-28">
            {{-- Subtle Background Canvas Texture --}}
            <div class="absolute inset-0 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:24px_24px] opacity-40 pointer-events-none"></div>
            
            <div class="max-w-7xl mx-auto px-6 sm:px-8 relative z-10">
                <div class="grid lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                    
                    {{-- Text Column --}}
                    <div class="lg:col-span-7">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-zinc-100 border border-zinc-200/80 text-zinc-700 text-xs font-mono-tag mb-6">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>OFFICIAL CURATED INTERNSHIP PROGRAM</span>
                        </div>

                        <h2 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-zinc-950 tracking-tight leading-[1.08] mb-6">
                            Pusat Eksplorasi Data & <span class="underline decoration-zinc-300 underline-offset-8">Talenta Statistik</span>
                        </h2>

                        <p class="text-base sm:text-lg text-zinc-500 max-w-xl leading-relaxed mb-8">
                            Program magang terpadu Badan Pusat Statistik Kota Surabaya. Kelola administrasi berkas, presensi berbasis lokasi, dan logbook karya dalam satu kanvas digital yang presisi dan transparan.
                        </p>

                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                            @guest
                                <a href="{{ route('pendaftar.register') }}" 
                                   class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-zinc-950 text-white rounded-xl hover:bg-zinc-800 transition-all font-semibold text-xs tracking-wide shadow-md hover:shadow-lg">
                                    <span>Ajukan Permohonan Sekarang</span>
                                    <span class="font-mono-tag text-[11px] text-zinc-400">[PASS #MINT]</span>
                                </a>
                            @endguest
                            <a href="#kuota-magang" 
                               class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-zinc-100 text-zinc-800 border border-zinc-200/80 rounded-xl hover:bg-zinc-200/80 transition-all font-semibold text-xs text-center">
                                <svg class="w-4 h-4 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                <span>Eksplorasi Kuota Terbuka</span>
                            </a>
                        </div>

                        {{-- Metadata Metrics Tag --}}
                        <div class="mt-10 pt-6 border-t border-zinc-100 flex items-center gap-8 text-xs font-mono-tag text-zinc-400">
                            <div>
                                <span class="block text-zinc-950 font-bold text-sm">SURABAYA</span>
                                <span>PROV. JATIM</span>
                            </div>
                            <div class="h-6 w-px bg-zinc-200"></div>
                            <div>
                                <span class="block text-zinc-950 font-bold text-sm">WFO LURING</span>
                                <span>JAM KERJA KANTOR</span>
                            </div>
                            <div class="h-6 w-px bg-zinc-200"></div>
                            <div>
                                <span class="block text-emerald-600 font-bold text-sm">TRANSPARAN</span>
                                <span>KUOTA REAL-TIME</span>
                            </div>
                        </div>
                    </div>

                    {{-- Visual Exhibition Placard Column --}}
                    <div class="lg:col-span-5">
                        <div class="bg-gradient-to-br from-zinc-900 to-zinc-950 text-white rounded-3xl p-7 border border-zinc-800 shadow-2xl relative overflow-hidden group">
                            {{-- Ambient Glow --}}
                            <div class="absolute -top-24 -right-24 w-60 h-60 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>

                            <div class="flex items-center justify-between mb-8 pb-4 border-b border-zinc-800">
                                <div>
                                    <p class="text-[10px] font-mono-tag uppercase tracking-wider text-zinc-400">EXHIBITION ARCHIVE</p>
                                    <h3 class="text-sm font-bold text-zinc-100">BPS Internship Edition</h3>
                                </div>
                                <div class="bg-white rounded-xl p-1.5 shadow-sm">
                                    <x-logo-bps class="h-6 w-auto object-contain" />
                                </div>
                            </div>

                            <div class="space-y-4 font-mono-tag">
                                <div class="p-3.5 rounded-2xl bg-zinc-800/40 border border-zinc-700/50 flex items-center justify-between">
                                    <span class="text-xs text-zinc-400">STATUS ALOKASI</span>
                                    <span class="text-xs font-bold text-emerald-400 flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                                        LIVE ON-CHAIN
                                    </span>
                                </div>
                                <div class="p-3.5 rounded-2xl bg-zinc-800/40 border border-zinc-700/50 flex items-center justify-between">
                                    <span class="text-xs text-zinc-400">TOTAL KAPASITAS</span>
                                    <span class="text-xs font-bold text-zinc-200">{{ $requirementData['total_quota'] ?? 45 }} SLOT TERSEDIA</span>
                                </div>
                                <div class="p-3.5 rounded-2xl bg-zinc-800/40 border border-zinc-700/50 flex items-center justify-between">
                                    <span class="text-xs text-zinc-400">MAHASISWA AKTIF</span>
                                    <span class="text-xs font-bold text-indigo-300">{{ $requirementData['total_active'] ?? 0 }} MAHASISWA</span>
                                </div>
                            </div>

                            <div class="mt-8 pt-4 border-t border-zinc-800/60 flex items-center justify-between text-[11px] text-zinc-500 font-mono-tag">
                                <span>VERIFICATION: BPS-3578</span>
                                <span class="text-zinc-400">EDITION {{ date('Y') }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Requirement & Kuota Magang (Curated Collections Grid) -->
        <section id="kuota-magang" class="py-24 bg-[#FAFAFA] relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 relative z-10">
                
                {{-- Section Header --}}
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-16 pb-6 border-b border-zinc-200">
                    <div>
                        <div class="inline-flex items-center gap-2 text-xs font-mono-tag text-zinc-400 uppercase tracking-widest mb-2">
                            <span>// COLLECTION OVERVIEW</span>
                        </div>
                        <h3 class="text-3xl lg:text-4xl font-extrabold text-zinc-950 tracking-tight">
                            Persyaratan & <span class="text-zinc-500 font-light">Alokasi Kuota</span> Magang
                        </h3>
                    </div>
                    <p class="text-xs text-zinc-500 font-mono-tag max-w-sm">
                        Ketersediaan slot diperbarui secara dinamis langsung dari basis data mahasiswa aktif BPS Kota Surabaya.
                    </p>
                </div>

                {{-- Bento Layout: Museum Placard Style --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">

                    {{-- ── 1. Kartu Utama: Total Slot & Status (Hero Placard - 2 Col) ── --}}
                    <div class="lg:col-span-2 bg-zinc-950 text-white rounded-3xl p-8 sm:p-10 relative overflow-hidden shadow-2xl flex flex-col justify-between border border-zinc-800 group">
                        {{-- Ambient Art Lighting --}}
                        <div class="absolute -right-20 -bottom-20 w-80 h-80 rounded-full bg-emerald-500/10 blur-3xl pointer-events-none"></div>

                        <div>
                            {{-- Header Kartu: Status Badge & Logo BPS --}}
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    @if(($requirementData['status'] ?? 'soon') === 'open')
                                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-950/80 border border-emerald-500/30 text-emerald-300 text-xs font-mono-tag shadow-xs">
                                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                            <span>STATUS: DI BUKA</span>
                                        </div>
                                    @elseif(($requirementData['status'] ?? 'soon') === 'soon')
                                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-950/80 border border-amber-500/30 text-amber-300 text-xs font-mono-tag shadow-xs">
                                            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                            <span>STATUS: SEGERA DIBUKA</span>
                                        </div>
                                    @else
                                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-950/80 border border-rose-500/30 text-rose-300 text-xs font-mono-tag shadow-xs">
                                            <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                                            <span>STATUS: DITUTUP</span>
                                        </div>
                                    @endif

                                    <h4 class="text-2xl sm:text-3xl font-extrabold tracking-tight mt-4 text-zinc-100">
                                        Total Ketersediaan Kuota
                                    </h4>
                                </div>

                                {{-- Logo BPS Placard Frame --}}
                                <div class="bg-white rounded-2xl p-2.5 sm:p-3 shadow-md shrink-0 flex items-center justify-center border border-zinc-200"
                                     title="Badan Pusat Statistik Kota Surabaya">
                                    <x-logo-bps class="h-8 sm:h-9 w-auto object-contain" />
                                </div>
                            </div>

                            {{-- Highlight Angka Sisa Slot ("43 dari 45 slot") --}}
                            <div class="mt-8">
                                <p class="text-xs uppercase tracking-widest text-zinc-400 font-mono-tag mb-1">
                                    AVAILABLE SLOTS / TOTAL QUOTA
                                </p>
                                <div class="flex items-baseline gap-3 flex-wrap">
                                    <span class="text-6xl sm:text-7xl font-extrabold tracking-tight text-white count-up-number font-mono-tag"
                                          data-target="{{ $requirementData['total_remaining'] ?? 0 }}">
                                        0
                                    </span>
                                    <span class="text-xl sm:text-2xl text-zinc-400 font-light">
                                        dari <strong class="text-white font-mono-tag">{{ $requirementData['total_quota'] ?? 0 }}</strong> slot terbuka
                                    </span>
                                </div>

                                {{-- Status Note --}}
                                @if(!empty($requirementData['status_note']))
                                    <div class="mt-4 inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-zinc-900/90 border border-zinc-800 text-xs text-zinc-300 font-mono-tag leading-relaxed">
                                        <svg class="w-3.5 h-3.5 text-amber-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>{{ $requirementData['status_note'] }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Progress Bar Total Slot --}}
                        <div class="mt-8 pt-6 border-t border-zinc-800">
                            <div class="flex items-center justify-between text-xs font-mono-tag text-zinc-400 mb-2.5">
                                <span>Mahasiswa Magang Aktif: <strong class="text-white font-bold">{{ $requirementData['total_active'] ?? 0 }} orang</strong></span>
                                <span>{{ $requirementData['total_percentage'] ?? 0 }}% terisi</span>
                            </div>
                            <div class="w-full bg-zinc-800 h-2.5 rounded-full overflow-hidden p-0.5">
                                <div class="bg-white h-full rounded-full transition-all duration-1000 ease-out progress-bar-animated"
                                     data-width="{{ $requirementData['total_percentage'] ?? 0 }}%"
                                     style="width: 0%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- ── 2. Kartu Persyaratan: Criteria Placard (1 Col) ── --}}
                    <div class="bg-white rounded-3xl p-8 border border-zinc-200/80 shadow-sm flex flex-col justify-between hover:border-zinc-300 transition-all duration-300">
                        <div>
                            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-zinc-100">
                                <div class="w-9 h-9 rounded-xl bg-zinc-950 text-white flex items-center justify-center font-mono-tag text-xs font-bold shrink-0">
                                    01
                                </div>
                                <div>
                                    <h4 class="text-base font-extrabold text-zinc-950 tracking-tight">Kriteria Kurasi</h4>
                                    <p class="text-[11px] font-mono-tag text-zinc-400 uppercase">Admission Criteria</p>
                                </div>
                            </div>

                            {{-- Checklist Items --}}
                            <ul class="space-y-3.5">
                                @forelse($requirementData['requirements'] ?? [] as $req)
                                    <li class="flex items-start gap-2.5 text-xs text-zinc-700 leading-relaxed">
                                        <span class="w-1.5 h-1.5 rounded-full bg-zinc-950 mt-1.5 shrink-0"></span>
                                        <span>{{ $req }}</span>
                                    </li>
                                @empty
                                    <li class="text-xs text-zinc-400 italic">Belum ada kriteria yang ditentukan.</li>
                                @endforelse
                            </ul>
                        </div>

                        {{-- Catatan Persyaratan Tambahan --}}
                        @if(!empty($requirementData['requirements_note']))
                            <div class="mt-6 pt-4 border-t border-zinc-100">
                                <div class="text-[11px] text-zinc-500 leading-relaxed bg-zinc-50 p-3.5 rounded-2xl border border-zinc-200/60 font-mono-tag">
                                    <span class="text-zinc-900 font-bold block mb-0.5">// CATATAN KURATOR</span>
                                    {{ $requirementData['requirements_note'] }}
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- ── 3. Kartu Kelompok Jurusan (Curated Collection Cards) ── --}}
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
                                default  => 'bg-zinc-950',
                            };

                            $majors = is_array($grp->majors ?? null) ? $grp->majors : ($grp->majors ?? []);
                        @endphp
                        <div class="bg-white rounded-3xl p-7 border border-zinc-200/80 shadow-sm flex flex-col justify-between hover:border-zinc-400 hover:shadow-lg transition-all duration-300">
                            <div>
                                {{-- Header Kelompok --}}
                                <div class="flex items-start justify-between gap-3 mb-4">
                                    <div>
                                        <span class="text-[10px] font-mono-tag uppercase tracking-widest text-zinc-400 block mb-1">
                                            {{ !empty($grp->is_catch_all) ? '// GENERAL BATCH' : '// CURATED STREAM' }}
                                        </span>
                                        <h4 class="text-lg font-extrabold text-zinc-950 tracking-tight leading-snug">
                                            {{ $grp->name ?? 'Kelompok Jurusan' }}
                                        </h4>
                                    </div>

                                    {{-- Badge Kuota --}}
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-mono-tag font-bold border shrink-0 {{ $badgeClass }}">
                                        @if($rem <= 0)
                                            PENUH
                                        @elseif($remPct <= 30)
                                            TERBATAS ({{ $rem }})
                                        @else
                                            TERSEDIA ({{ $rem }})
                                        @endif
                                    </span>
                                </div>

                                {{-- Angka Sisa Kuota Besar --}}
                                <div class="mt-4 flex items-baseline gap-2">
                                    <span class="text-4xl font-extrabold text-zinc-950 count-up-number font-mono-tag"
                                          data-target="{{ $rem }}">
                                        0
                                    </span>
                                    <span class="text-xs text-zinc-500 font-medium">
                                        slot tersisa dari <strong class="text-zinc-900 font-mono-tag">{{ $quota }}</strong> kuota
                                    </span>
                                </div>

                                {{-- Progress Bar --}}
                                <div class="w-full bg-zinc-100 h-2 rounded-full mt-3 overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-1000 ease-out progress-bar-animated {{ $barClass }}"
                                         data-width="{{ $pct }}%"
                                         style="width: 0%"></div>
                                </div>

                                {{-- Deretan Titik Slot (Slot Dots Visualization) --}}
                                <div class="flex items-center gap-1 mt-3 flex-wrap">
                                    @php
                                        $displayDots = min(20, $quota);
                                        $activeDots = $quota > 0 ? round(($active / $quota) * $displayDots) : 0;
                                    @endphp
                                    @for($i = 0; $i < $displayDots; $i++)
                                        <span class="w-1.5 h-1.5 rounded-full {{ $i < $activeDots ? 'bg-zinc-300' : 'bg-zinc-950' }}"
                                              title="{{ $i < $activeDots ? 'Terisi' : 'Tersedia' }}"></span>
                                    @endfor
                                    @if($quota > 20)
                                        <span class="text-[9px] text-zinc-400 font-mono-tag">+{{ $quota - 20 }}</span>
                                    @endif
                                </div>

                                {{-- Chip Jurusan yang Tercakup --}}
                                <div class="mt-6 pt-4 border-t border-zinc-100">
                                    <p class="text-[10px] font-mono-tag uppercase tracking-wider text-zinc-400 mb-2.5">Scope Jurusan / Program Studi:</p>
                                    <div class="flex flex-wrap gap-1.5">
                                        @forelse($majors as $major)
                                            @php
                                                $majorName = is_array($major) ? ($major['name'] ?? '') : ($major->name ?? (string) $major);
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-zinc-50 text-zinc-800 text-[11px] font-mono-tag border border-zinc-200/80">
                                                {{ $majorName }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-zinc-400 italic">Mencakup seluruh jurusan lainnya.</span>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            {{-- Footer Kartu --}}
                            <div class="mt-6 pt-3.5 border-t border-zinc-100 flex items-center justify-between text-xs font-mono-tag text-zinc-400">
                                <span>AKTIF: {{ $active }} MAHASISWA</span>
                                <span>{{ $pct }}% TERISI</span>
                            </div>
                        </div>
                    @empty
                        <div class="lg:col-span-3 bg-white rounded-3xl p-10 border border-zinc-200 text-center shadow-xs">
                            <p class="text-sm font-bold text-zinc-800 font-mono-tag">ALOKASI KUOTA JURUSAN SEDANG DISIAPKAN</p>
                        </div>
                    @endforelse

                </div>

                {{-- Timestamp Terakhir Diperbarui --}}
                <div class="mt-12 text-center text-xs text-zinc-400 font-mono-tag">
                    LEDGER TIMESTAMP:
                    <span class="font-semibold text-zinc-700">
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

        <!-- Alur Pendaftaran (Curatorial Intake Stages) -->
        <section id="alur" class="py-24 bg-white border-t border-zinc-200/80">
            <div class="max-w-7xl mx-auto px-6 sm:px-8">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-16 pb-6 border-b border-zinc-200">
                    <div>
                        <div class="inline-flex items-center gap-2 text-xs font-mono-tag text-zinc-400 uppercase tracking-widest mb-2">
                            <span>// INTAKE PROTOCOL</span>
                        </div>
                        <h3 class="text-3xl font-extrabold text-zinc-950 tracking-tight">Alur Pengajuan & Kurasi</h3>
                    </div>
                    <p class="text-xs text-zinc-500 font-mono-tag max-w-sm">
                        Empat tahapan terstruktur dari registrasi hingga penetapan resmi mahasiswa magang aktif.
                    </p>
                </div>

                <div class="grid md:grid-cols-4 gap-6">
                    <!-- Step 1 -->
                    <div class="bg-zinc-50/70 p-7 rounded-3xl border border-zinc-200/80 hover:border-zinc-400 transition-all flex flex-col justify-between">
                        <div>
                            <div class="w-10 h-10 rounded-xl bg-zinc-950 text-white flex items-center justify-center font-mono-tag text-sm font-bold mb-5">
                                01
                            </div>
                            <h4 class="text-base font-bold text-zinc-950 mb-2">Registrasi Akun</h4>
                            <p class="text-xs text-zinc-500 leading-relaxed">Buat identitas akun pendaftar dan verifikasi email resmi kampus Anda.</p>
                        </div>
                        <div class="mt-6 pt-3 border-t border-zinc-200/60 font-mono-tag text-[10px] text-zinc-400 uppercase">STAGE: IDENTIFIER</div>
                    </div>
                    <!-- Step 2 -->
                    <div class="bg-zinc-50/70 p-7 rounded-3xl border border-zinc-200/80 hover:border-zinc-400 transition-all flex flex-col justify-between">
                        <div>
                            <div class="w-10 h-10 rounded-xl bg-zinc-950 text-white flex items-center justify-center font-mono-tag text-sm font-bold mb-5">
                                02
                            </div>
                            <h4 class="text-base font-bold text-zinc-950 mb-2">Upload Dokumen</h4>
                            <p class="text-xs text-zinc-500 leading-relaxed">Unggah berkas terkurasi: Surat izin kampus, CV mutakhir, dan transkrip nilai akademik.</p>
                        </div>
                        <div class="mt-6 pt-3 border-t border-zinc-200/60 font-mono-tag text-[10px] text-zinc-400 uppercase">STAGE: SUBMISSION</div>
                    </div>
                    <!-- Step 3 -->
                    <div class="bg-zinc-50/70 p-7 rounded-3xl border border-zinc-200/80 hover:border-zinc-400 transition-all flex flex-col justify-between">
                        <div>
                            <div class="w-10 h-10 rounded-xl bg-zinc-950 text-white flex items-center justify-center font-mono-tag text-sm font-bold mb-5">
                                03
                            </div>
                            <h4 class="text-base font-bold text-zinc-950 mb-2">Kurasi Berkas</h4>
                            <p class="text-xs text-zinc-500 leading-relaxed">Admin BPS menelaah kesesuaian alokasi jurusan dan kelayakan dokumen pengajuan.</p>
                        </div>
                        <div class="mt-6 pt-3 border-t border-zinc-200/60 font-mono-tag text-[10px] text-zinc-400 uppercase">STAGE: CURATION</div>
                    </div>
                    <!-- Step 4 -->
                    <div class="bg-zinc-50/70 p-7 rounded-3xl border border-zinc-200/80 hover:border-zinc-400 transition-all flex flex-col justify-between">
                        <div>
                            <div class="w-10 h-10 rounded-xl bg-zinc-950 text-white flex items-center justify-center font-mono-tag text-sm font-bold mb-5">
                                04
                            </div>
                            <h4 class="text-base font-bold text-zinc-950 mb-2">Whitelist & Masuk</h4>
                            <p class="text-xs text-zinc-500 leading-relaxed">Persetujuan akhir membuka akses penuh akun sebagai Mahasiswa Magang di Artist Studio.</p>
                        </div>
                        <div class="mt-6 pt-3 border-t border-zinc-200/60 font-mono-tag text-[10px] text-zinc-400 uppercase">STAGE: ADMISSION</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Fitur Studio Section -->
        <section id="fitur" class="py-24 bg-[#FAFAFA] border-t border-zinc-200/80">
            <div class="max-w-7xl mx-auto px-6 sm:px-8">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-16 pb-6 border-b border-zinc-200">
                    <div>
                        <div class="inline-flex items-center gap-2 text-xs font-mono-tag text-zinc-400 uppercase tracking-widest mb-2">
                            <span>// STUDIO TOOLS</span>
                        </div>
                        <h3 class="text-3xl font-extrabold text-zinc-950 tracking-tight">Fasilitas Mahasiswa Magang</h3>
                    </div>
                    <p class="text-xs text-zinc-500 font-mono-tag max-w-sm">
                        Instrumen digital mandiri untuk pencatatan kehadiran, karya harian, dan izin operasional.
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Fitur 1 -->
                    <div class="p-8 bg-white rounded-3xl border border-zinc-200/80 shadow-sm hover:border-zinc-400 transition-all">
                        <div class="w-12 h-12 bg-zinc-950 text-white rounded-2xl flex items-center justify-center mb-6 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <h4 class="text-lg font-bold text-zinc-950 mb-2">Presensi Geolocation</h4>
                        <p class="text-zinc-500 leading-relaxed text-xs">Pencatatan jam masuk dan pulang terverifikasi titik koordinat GPS kantor BPS Kota Surabaya (radius 1 km).</p>
                    </div>
                    <!-- Fitur 2 -->
                    <div class="p-8 bg-white rounded-3xl border border-zinc-200/80 shadow-sm hover:border-zinc-400 transition-all">
                        <div class="w-12 h-12 bg-zinc-950 text-white rounded-2xl flex items-center justify-center mb-6 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <h4 class="text-lg font-bold text-zinc-950 mb-2">Logbook Karya Digital</h4>
                        <p class="text-zinc-500 leading-relaxed text-xs">Arsip catatan aktivitas dan kontribusi tugas harian yang terkoneksi langsung dengan verifikasi pembimbing BPS.</p>
                    </div>
                    <!-- Fitur 3 -->
                    <div class="p-8 bg-white rounded-3xl border border-zinc-200/80 shadow-sm hover:border-zinc-400 transition-all">
                        <div class="w-12 h-12 bg-zinc-950 text-white rounded-2xl flex items-center justify-center mb-6 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h4 class="text-lg font-bold text-zinc-950 mb-2">Absence Pass (Perizinan)</h4>
                        <p class="text-zinc-500 leading-relaxed text-xs">Pengajuan izin daring yang akuntabel dengan pengunggahan dokumen bukti resmi secara cepat dan tercatat rapi.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer (Minimalist Exhibition Colophon) -->
    <footer class="bg-zinc-950 text-zinc-400 py-16 border-t border-zinc-800 font-mono-tag text-xs">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-white text-zinc-950 rounded-lg flex items-center justify-center font-bold text-xs">
                    SB
                </div>
                <div>
                    <span class="text-white font-bold tracking-tight block">SIBOOK-V5</span>
                    <span class="text-[10px] text-zinc-500">Badan Pusat Statistik Kota Surabaya</span>
                </div>
            </div>

            <p class="text-zinc-500 text-[11px] leading-relaxed">
                &copy; {{ date('Y') }} BPS Kota Surabaya. Sistem Informasi Buku Magang.<br>
                All rights reserved. Digital Provenance Edition.
            </p>

            <div class="flex gap-6 text-[11px]">
                <a href="https://surabayakota.bps.go.id" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors">PORTAL BPS</a>
                <a href="https://sps.bps.go.id" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors">SPS LAYANAN</a>
                <a href="mailto:bps3578@bps.go.id" class="hover:text-white transition-colors">KONTAK</a>
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
                }, { threshold: 0.2 });

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
                            observer.unobserve(el);

                            if (prefersReducedMotion || target === 0) {
                                el.textContent = target;
                                return;
                            }

                            const duration = 1200;
                            const start = 0;
                            const startTime = performance.now();

                            function easeOutQuart(x) {
                                return 1 - Math.pow(1 - x, 4);
                            }

                            function updateCount(currentTime) {
                                const elapsed = currentTime - startTime;
                                const progress = Math.min(elapsed / duration, 1);
                                const current = Math.floor(easeOutQuart(progress) * (target - start) + start);
                                el.textContent = current;

                                if (progress < 1) {
                                    requestAnimationFrame(updateCount);
                                } else {
                                    el.textContent = target;
                                }
                            }

                            requestAnimationFrame(updateCount);
                        }
                    });
                }, { threshold: 0.3 });

                countElements.forEach(el => countObserver.observe(el));
            }
        });
    </script>
</body>
</html>
