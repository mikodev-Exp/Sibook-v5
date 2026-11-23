<aside class="w-64 bg-white/90 backdrop-blur-md border-r border-zinc-200/80 h-full flex flex-col justify-between py-5 px-3.5 select-none">
    <div class="space-y-1">
        <div class="px-3 pb-2 pt-1">
            <span class="text-[10px] font-mono-tag uppercase tracking-wider text-zinc-400 font-bold">Master Console</span>
        </div>

        <a href="{{ route('superuser.dashboard') }}"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13px] transition-all duration-150
                  {{ request()->routeIs('superuser.dashboard') 
                     ? 'bg-zinc-950 text-white font-semibold shadow-sm' 
                     : 'text-zinc-600 hover:text-zinc-950 hover:bg-zinc-100/80 font-normal' }}">
            <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('superuser.dashboard') ? 'text-white' : 'text-zinc-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                <rect x="3" y="14" width="7" height="7" rx="1.5"/>
            </svg>
            <span>Dashboard</span>
        </a>
        
        <a href="{{ route('superuser.permohonan.index') }}"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13px] transition-all duration-150
                  {{ request()->routeIs('superuser.permohonan.*') 
                     ? 'bg-zinc-950 text-white font-semibold shadow-sm' 
                     : 'text-zinc-600 hover:text-zinc-950 hover:bg-zinc-100/80 font-normal' }}">
            <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('superuser.permohonan.*') ? 'text-white' : 'text-zinc-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Finalisasi Magang</span>
        </a>

        <a href="{{ route('superuser.pengaturan.index') }}"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13px] transition-all duration-150
                  {{ request()->routeIs('superuser.pengaturan.*') 
                     ? 'bg-zinc-950 text-white font-semibold shadow-sm' 
                     : 'text-zinc-600 hover:text-zinc-950 hover:bg-zinc-100/80 font-normal' }}">
            <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('superuser.pengaturan.*') ? 'text-white' : 'text-zinc-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>Pengaturan Sistem</span>
        </a>
    </div>

    <div class="px-3 pt-4 border-t border-zinc-100">
        <div class="flex items-center justify-between text-zinc-400 text-[11px] font-mono-tag">
            <span>SUPER USER</span>
            <span class="text-indigo-600 font-semibold">ROOT</span>
        </div>
    </div>
</aside>
