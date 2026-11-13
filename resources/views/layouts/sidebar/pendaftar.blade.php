<aside class="w-64 bg-gray-800 text-gray-100 min-h-screen flex flex-col">
    <div class="p-6 border-b border-gray-700">
        <h2 class="text-xl font-bold">SIBOOK</h2>
        <p class="text-xs text-gray-400 mt-1">Pendaftar</p>
    </div>
    <nav class="flex-1 p-4 space-y-1">
        <a href="{{ route('pendaftar.dashboard') }}"
           class="block px-4 py-2 rounded {{ request()->routeIs('pendaftar.dashboard') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-700' }}">
            Dashboard
        </a>
        
        <!-- Menu Permohonan Magang -->
        <a href="{{ route('pendaftar.ujian.index') }}"
            class="block px-4 py-2 rounded {{ request()->routeIs('pendaftar.ujian.*') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-700' }}">
            Ujian Kompetensi
        </a>
    </nav>
</aside>