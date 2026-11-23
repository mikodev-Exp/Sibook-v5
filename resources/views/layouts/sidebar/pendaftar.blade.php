<aside class="w-64 bg-white/90 backdrop-blur-md border-r border-zinc-200/80 h-full flex flex-col justify-between py-5 px-3.5 select-none">
    <div class="space-y-1">
        <div class="px-3 pb-2 pt-1">
            <span class="text-[10px] font-mono-tag uppercase tracking-wider text-zinc-400 font-bold">Applicant Terminal</span>
        </div>

        <a href="{{ route('pendaftar.dashboard') }}"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13px] transition-all duration-150
                  {{ request()->routeIs('pendaftar.dashboard') 
                     ? 'bg-zinc-950 text-white font-semibold shadow-sm' 
                     : 'text-zinc-600 hover:text-zinc-950 hover:bg-zinc-100/80 font-normal' }}">
            <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('pendaftar.dashboard') ? 'text-white' : 'text-zinc-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <rect x="3" y="3" width="18" height="18" rx="2" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />
            </svg>
            <span>Status Permohonan</span>
        </a>
        
        <a href="{{ route('pendaftar.ujian.index') }}"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13px] transition-all duration-150
                  {{ request()->routeIs('pendaftar.ujian.*') 
                     ? 'bg-zinc-950 text-white font-semibold shadow-sm' 
                     : 'text-zinc-600 hover:text-zinc-950 hover:bg-zinc-100/80 font-normal' }}">
            <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('pendaftar.ujian.*') ? 'text-white' : 'text-zinc-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span>Ujian Kompetensi</span>
        </a>
    </div>

    <div class="px-3 pt-4 border-t border-zinc-100">
        <div class="flex items-center justify-between text-zinc-400 text-[11px] font-mono-tag">
            <span>PASS #{{ str_pad(auth()->id(), 4, '0', STR_PAD_LEFT) }}</span>
            <span class="text-emerald-600 font-semibold">VERIFIED</span>
        </div>
    </div>
</aside>
