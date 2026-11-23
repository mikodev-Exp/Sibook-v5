@php
    $permohonanPendingCount = \App\Models\PermohonanMagang::where('status', 'submitted')->count();
    $userRegisterCount = \App\Models\User::role('pendaftar')->count();
    $perizinanPendingCount = \App\Models\Perizinan::where('status', 'submitted')->count();
    $logbookPendingCount = \App\Models\Logbook::where('status', 'submitted')->count();
@endphp

<aside class="w-64 bg-white/90 backdrop-blur-md border-r border-zinc-200/80 h-full flex flex-col justify-between py-5 px-3.5 select-none">
    {{-- Navigation List --}}
    <div class="space-y-1">
        <div class="px-3 pb-2 pt-1">
            <span class="text-[10px] font-mono-tag uppercase tracking-wider text-zinc-400 font-bold">Curatorial Menu</span>
        </div>

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13px] transition-all duration-150
                  {{ request()->routeIs('admin.dashboard') 
                     ? 'bg-zinc-950 text-white font-semibold shadow-sm' 
                     : 'text-zinc-600 hover:text-zinc-950 hover:bg-zinc-100/80 font-normal' }}">
            <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-zinc-400' }}" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                <rect x="3" y="14" width="7" height="7" rx="1.5"/>
            </svg>
            <span>Dashboard</span>
        </a>

        {{-- Permohonan Magang --}}
        <a href="{{ route('admin.permohonan.index') }}"
           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-[13px] transition-all duration-150
                  {{ request()->routeIs('admin.permohonan.*') 
                     ? 'bg-zinc-950 text-white font-semibold shadow-sm' 
                     : 'text-zinc-600 hover:text-zinc-950 hover:bg-zinc-100/80 font-normal' }}">
            <span class="flex items-center gap-3">
                <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.permohonan.*') ? 'text-white' : 'text-zinc-400' }}" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 9h2m-2 4h6m-6 4h6"/>
                </svg>
                <span>Permohonan Magang</span>
            </span>
            @if($permohonanPendingCount > 0)
                <span class="font-mono-tag text-[10.5px] px-2 py-0.5 rounded-full {{ request()->routeIs('admin.permohonan.*') ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                    {{ $permohonanPendingCount }}
                </span>
            @endif
        </a>

        {{-- Mahasiswa Magang --}}
        <a href="{{ route('admin.mahasiswa.index') }}"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13px] transition-all duration-150
                  {{ request()->routeIs('admin.mahasiswa.*') 
                     ? 'bg-zinc-950 text-white font-semibold shadow-sm' 
                     : 'text-zinc-600 hover:text-zinc-950 hover:bg-zinc-100/80 font-normal' }}">
            <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.mahasiswa.*') ? 'text-white' : 'text-zinc-400' }}" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <circle cx="12" cy="12" r="9"/>
                <circle cx="12" cy="10" r="3"/>
                <path stroke-linecap="round" d="M6.168 18.849A4 4 0 0110 16h4a4 4 0 013.832 2.849"/>
            </svg>
            <span>Mahasiswa Magang</span>
        </a>

        {{-- Persyaratan & Kuota Magang --}}
        <a href="{{ route('admin.persyaratan-magang.index') }}"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13px] transition-all duration-150
                  {{ request()->routeIs('admin.persyaratan-magang.*', 'admin.requirement-magang.*') 
                     ? 'bg-zinc-950 text-white font-semibold shadow-sm' 
                     : 'text-zinc-600 hover:text-zinc-950 hover:bg-zinc-100/80 font-normal' }}">
            <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.persyaratan-magang.*', 'admin.requirement-magang.*') ? 'text-white' : 'text-zinc-400' }}" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            <span>Persyaratan & Kuota</span>
        </a>

        {{-- Logbook --}}
        <a href="{{ route('admin.logbook.index') }}"
           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-[13px] transition-all duration-150
                  {{ request()->routeIs('admin.logbook.*') 
                     ? 'bg-zinc-950 text-white font-semibold shadow-sm' 
                     : 'text-zinc-600 hover:text-zinc-950 hover:bg-zinc-100/80 font-normal' }}">
            <div class="flex items-center gap-3 min-w-0">
                <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.logbook.*') ? 'text-white' : 'text-zinc-400' }}" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span>Logbook</span>
            </div>
            @if($logbookPendingCount > 0)
                <span class="font-mono-tag text-[10.5px] px-2 py-0.5 rounded-full {{ request()->routeIs('admin.logbook.*') ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                    {{ $logbookPendingCount }}
                </span>
            @endif
        </a>

        {{-- Perizinan --}}
        <a href="{{ route('admin.perizinan.index') }}"
           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-[13px] transition-all duration-150
                  {{ request()->routeIs('admin.perizinan.*') 
                     ? 'bg-zinc-950 text-white font-semibold shadow-sm' 
                     : 'text-zinc-600 hover:text-zinc-950 hover:bg-zinc-100/80 font-normal' }}">
            <div class="flex items-center gap-3 min-w-0">
                <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.perizinan.*') ? 'text-white' : 'text-zinc-400' }}" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>Perizinan</span>
            </div>
            @if($perizinanPendingCount > 0)
                <span class="font-mono-tag text-[10.5px] px-2 py-0.5 rounded-full {{ request()->routeIs('admin.perizinan.*') ? 'bg-white/20 text-white' : 'bg-orange-100 text-orange-800 border border-orange-200' }}">
                    {{ $perizinanPendingCount }}
                </span>
            @endif
        </a>

        {{-- Presensi Magang --}}
        <a href="{{ route('admin.presensi.index') }}"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13px] transition-all duration-150
                  {{ request()->routeIs('admin.presensi.index') 
                     ? 'bg-zinc-950 text-white font-semibold shadow-sm' 
                     : 'text-zinc-600 hover:text-zinc-950 hover:bg-zinc-100/80 font-normal' }}">
            <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.presensi.index') ? 'text-white' : 'text-zinc-400' }}" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            <span>Rekap Presensi</span>
        </a>

        {{-- Presensi Manual --}}
        <a href="{{ route('admin.presensi.manual') }}"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13px] transition-all duration-150
                  {{ request()->routeIs('admin.presensi.manual') 
                     ? 'bg-zinc-950 text-white font-semibold shadow-sm' 
                     : 'text-zinc-600 hover:text-zinc-950 hover:bg-zinc-100/80 font-normal' }}">
            <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.presensi.manual') ? 'text-white' : 'text-zinc-400' }}" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>Presensi Manual</span>
        </a>

        {{-- User Register --}}
        <a href="{{ route('admin.user-register.index') }}"
           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-[13px] transition-all duration-150
                  {{ request()->routeIs('admin.user-register.*') 
                     ? 'bg-zinc-950 text-white font-semibold shadow-sm' 
                     : 'text-zinc-600 hover:text-zinc-950 hover:bg-zinc-100/80 font-normal' }}">
            <span class="flex items-center gap-3">
                <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.user-register.*') ? 'text-white' : 'text-zinc-400' }}" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                <span>User Register</span>
            </span>
            @if($userRegisterCount > 0)
                <span class="font-mono-tag text-[10.5px] px-2 py-0.5 rounded-full {{ request()->routeIs('admin.user-register.*') ? 'bg-white/20 text-white' : 'bg-zinc-100 text-zinc-700 border border-zinc-200' }}">
                    {{ $userRegisterCount }}
                </span>
            @endif
        </a>
    </div>

    {{-- Bottom Section: System Indicator --}}
    <div class="px-3 pt-4 border-t border-zinc-100">
        <div class="flex items-center justify-between text-zinc-400 text-[11px] font-mono-tag">
            <span>BPS SURABAYA</span>
            <span class="flex items-center gap-1.5 text-emerald-600 font-semibold">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                ACTIVE
            </span>
        </div>
    </div>
</aside>
