<aside class="w-64 bg-gray-800 text-gray-100 min-h-screen flex flex-col">
    <div class="p-6 border-b border-gray-700">
        <h2 class="text-xl font-bold">SIBOOK</h2>
        <p class="text-xs text-gray-400 mt-1">Mahasiswa</p>
    </div>
    <nav class="flex-1 p-4 space-y-1">
        <a href="{{ route('mahasiswa.dashboard') }}"
           class="block px-4 py-2 rounded {{ request()->routeIs('mahasiswa.dashboard') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-700' }}">
            Dashboard
        </a>
        
        <!-- Menu Presensi -->
        <a href="{{ route('mahasiswa.presensi.index') }}"
            class="block px-4 py-2 rounded {{ request()->routeIs('mahasiswa.presensi.*') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-700' }}">
            Presensi
        </a>
        
        <!-- Menu Logbook -->
        <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700">
            Logbook
        </a>
        
        <!-- Menu Perizinan -->
        <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700">
            Perizinan
        </a>
    </nav>
</aside>