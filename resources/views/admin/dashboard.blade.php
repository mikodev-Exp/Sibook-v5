<aside class="w-64 bg-gray-800 text-gray-100 h-full flex flex-col overflow-y-auto">
    <div class="p-6 border-b border-gray-700">
        <h2 class="text-xl font-bold">SIBOOK</h2>
        <p class="text-xs text-gray-400 mt-1">Admin Panel</p>
    </div>
    <nav class="flex-1 p-4 space-y-1">
        <a href="{{ route('admin.dashboard') }}"
           class="block px-4 py-2 rounded {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-700' }}">
            Dashboard
        </a>
        
        <a href="{{ route('admin.permohonan.index') }}"
           class="block px-4 py-2 rounded {{ request()->routeIs('admin.permohonan.*') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-700' }}">
            Permohonan Magang
        </a>

        <a href="{{ route('admin.presensi.index') }}"
           class="block px-4 py-2 rounded {{ request()->routeIs('admin.presensi.*') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-700' }}">
            Presensi Magang
        </a>

        <a href="{{ route('admin.perizinan.index') }}"
           class="block px-4 py-2 rounded {{ request()->routeIs('admin.perizinan.*') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-700' }}">
            Perizinan
        </a>

        <a href="{{ route('admin.user-register.index') }}"
           class="block px-4 py-2 rounded {{ request()->routeIs('admin.user-register.*') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-700' }}">
            User Register
        </a>
    </nav>
</aside>