@php
    $pendingIzin = auth()->user()->perizinans()->where('status', 'submitted')->count();
    $draftLogbook = auth()->user()->logbooks()->where('status', 'draft')->count();
@endphp

<aside class="w-64 bg-white/90 backdrop-blur-md border-r border-zinc-200/80 h-full flex flex-col justify-between py-5 px-3.5 select-none">
    {{-- Navigation List --}}
    <div class="space-y-1">
        <div class="px-3 pb-2 pt-1">
            <span class="text-[10px] font-mono-tag uppercase tracking-wider text-zinc-400 font-bold">Studio Menu</span>
        </div>

        {{-- Dashboard --}}
        <a href="{{ route('mahasiswa.dashboard') }}"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13px] transition-all duration-150
                  {{ request()->routeIs('mahasiswa.dashboard') 
                     ? 'bg-zinc-950 text-white font-semibold shadow-sm' 
                     : 'text-zinc-600 hover:text-zinc-950 hover:bg-zinc-100/80 font-normal' }}">
            <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('mahasiswa.dashboard') ? 'text-white' : 'text-zinc-400' }}" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>Studio Dashboard</span>
        </a>

        {{-- Presensi --}}
        <a href="{{ route('mahasiswa.presensi.index') }}"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13px] transition-all duration-150
                  {{ request()->routeIs('mahasiswa.presensi.*') 
                     ? 'bg-zinc-950 text-white font-semibold shadow-sm' 
                     : 'text-zinc-600 hover:text-zinc-950 hover:bg-zinc-100/80 font-normal' }}">
            <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('mahasiswa.presensi.*') ? 'text-white' : 'text-zinc-400' }}" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Presensi Harian</span>
        </a>

        {{-- Logbook --}}
        <a href="{{ route('mahasiswa.logbook.index') }}"
           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-[13px] transition-all duration-150
                  {{ request()->routeIs('mahasiswa.logbook.*') 
                     ? 'bg-zinc-950 text-white font-semibold shadow-sm' 
                     : 'text-zinc-600 hover:text-zinc-950 hover:bg-zinc-100/80 font-normal' }}">
            <div class="flex items-center gap-3 min-w-0">
                <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('mahasiswa.logbook.*') ? 'text-white' : 'text-zinc-400' }}" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span>Logbook Karya</span>
            </div>
            @if($draftLogbook > 0)
                <span class="font-mono-tag text-[10.5px] px-2 py-0.5 rounded-full {{ request()->routeIs('mahasiswa.logbook.*') ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                    {{ $draftLogbook }}
                </span>
            @endif
        </a>

        {{-- Perizinan --}}
        <a href="{{ route('mahasiswa.perizinan.index') }}"
           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-[13px] transition-all duration-150
                  {{ request()->routeIs('mahasiswa.perizinan.*') 
                     ? 'bg-zinc-950 text-white font-semibold shadow-sm' 
                     : 'text-zinc-600 hover:text-zinc-950 hover:bg-zinc-100/80 font-normal' }}">
            <div class="flex items-center gap-3 min-w-0">
                <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('mahasiswa.perizinan.*') ? 'text-white' : 'text-zinc-400' }}" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Izin / Absence Pass</span>
            </div>
            @if($pendingIzin > 0)
                <span class="font-mono-tag text-[10.5px] px-2 py-0.5 rounded-full {{ request()->routeIs('mahasiswa.perizinan.*') ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-800 border border-blue-200' }}">
                    {{ $pendingIzin }}
                </span>
            @endif
        </a>
    </div>

    {{-- Info Card Bawah --}}
    <div class="px-3 pt-4 border-t border-zinc-100">
        <div class="flex items-center justify-between text-zinc-400 text-[11px] font-mono-tag">
            <span>ARTIST STATUS</span>
            <span class="flex items-center gap-1.5 text-emerald-600 font-semibold">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                ACTIVE
            </span>
        </div>
    </div>
</aside>
