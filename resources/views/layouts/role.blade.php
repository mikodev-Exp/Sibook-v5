<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'SIBOOK') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100" x-data="{ sidebarOpen: false }">
    
    <!-- Overlay Hitam (Hanya muncul di HP) -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden" 
         @click="sidebarOpen = false"></div>

    <!-- Gunakan h-screen dan overflow-hidden agar layar utama tidak ikut ter-scroll -->
    <div class="h-screen flex overflow-hidden">
        
        <!-- Wrapper Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 transform transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 flex-shrink-0"
             :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
            @include('layouts.sidebar.' . $role)
        </div>

        <!-- Konten Utama -->
        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
            <!-- Header tetap di atas -->
            <header class="bg-white shadow-sm border-b border-gray-200 flex-shrink-0">
                <div class="px-6 py-4 flex justify-between items-center">
                    
                    <div class="flex items-center gap-4">
                        <!-- Tombol Hamburger (HP) -->
                        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700 focus:outline-none lg:hidden">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        
                        <h1 class="text-lg font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-600 hidden sm:block">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Area Konten (Hanya bagian ini yang akan ter-scroll) -->
            <main class="flex-1 overflow-y-auto p-6 bg-gray-100">
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded">
                        {{ session('error') }}
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>