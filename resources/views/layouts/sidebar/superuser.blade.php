<aside class="w-64 bg-gray-900 text-gray-100 h-full flex flex-col overflow-y-auto border-r border-gray-800">
    <div class="p-6 border-b border-gray-800">
        <h2 class="text-xl font-bold text-white">SIBOOK</h2>
        <p class="text-xs text-indigo-400 font-bold tracking-wider uppercase mt-1">Super User</p>
    </div>
    <nav class="flex-1 p-4 space-y-1">
        <a href="{{ route('superuser.dashboard') }}"
           class="block px-4 py-2 rounded {{ request()->routeIs('superuser.dashboard') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-800' }}">
            Dashboard
        </a>
        
        <a href="{{ route('superuser.permohonan.index') }}"
           class="block px-4 py-2 rounded {{ request()->routeIs('superuser.permohonan.*') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-800' }}">
            Finalisasi Magang
        </a>

        <a href="{{ route('superuser.pengaturan.index') }}"
           class="block px-4 py-2 rounded {{ request()->routeIs('superuser.pengaturan.*') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-800' }}">
            Pengaturan Sistem
        </a>
    </nav>
</aside>