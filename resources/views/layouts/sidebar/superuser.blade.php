<aside class="w-64 bg-gray-800 text-gray-100 min-h-screen flex flex-col">
    <div class="p-6 border-b border-gray-700">
        <h2 class="text-xl font-bold">SIBOOK</h2>
        <p class="text-xs text-gray-400 mt-1">Super User</p>
    </div>
    <nav class="flex-1 p-4 space-y-1">
        <a href="{{ route('superuser.dashboard') }}"
           class="block px-4 py-2 rounded {{ request()->routeIs('superuser.dashboard') ? 'bg-blue-600 text-white' : 'hover:bg-gray-700' }}">
            Dashboard
        </a>
        <span class="block px-4 py-2 rounded opacity-40 cursor-not-allowed" title="Segera hadir">Approval Magang</span>
        <span class="block px-4 py-2 rounded opacity-40 cursor-not-allowed" title="Segera hadir">Pengaturan</span>
        <span class="block px-4 py-2 opacity-40 cursor-not-allowed" title="Segera hadir">Users</span>
    </nav>
</aside>