<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'SiBook') }}</title>

    <!-- Typography: Figtree & Space Mono untuk estetika galeri modern & kuratorial -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800|space-mono:400,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Figtree', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .font-mono-tag {
            font-family: 'Space Mono', ui-monospace, monospace;
        }
        .gallery-border {
            border-color: rgba(228, 228, 231, 0.8);
        }
    </style>
</head>
<body class="bg-[#FAFAFA] text-[#09090B] antialiased" x-data="{ sidebarOpen: false, userDropdown: false }">
    
    {{-- Header Utama (Gallery Top Bar - Minimalist Frame) --}}
    <header class="h-16 bg-white/95 backdrop-blur-md border-b border-zinc-200/80 px-6 lg:px-8 flex items-center justify-between z-30 sticky top-0 flex-shrink-0">
        <div class="flex items-center gap-4">
            {{-- Tombol Mobile --}}
            <button @click="sidebarOpen = !sidebarOpen" class="text-zinc-500 hover:text-zinc-900 focus:outline-none lg:hidden">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            {{-- Brand & Desk Identifier --}}
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-8 h-8 rounded-lg bg-zinc-950 text-white flex items-center justify-center font-bold text-xs tracking-tighter shadow-sm group-hover:scale-105 transition-transform">
                    SB
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-[17px] font-extrabold tracking-tight text-zinc-950">
                        SiBook
                    </span>
                    <span class="hidden sm:inline-block text-[10px] font-mono-tag uppercase tracking-wider px-2 py-0.5 rounded-full bg-zinc-100 text-zinc-600 border border-zinc-200/60">
                        {{ match($role ?? '') {
                            'admin' => 'Curator Desk',
                            'mahasiswa' => 'Artist Studio',
                            'pendaftar' => 'Applicant Pass',
                            'superuser' => 'Master Console',
                            default => 'Portal'
                        } }}
                    </span>
                </div>
            </a>
        </div>

        @php
            $name = auth()->user()->name ?? 'User';
            $words = preg_split('/\s+/', trim($name));
            $initials = '';
            foreach (array_slice($words, 0, 2) as $w) {
                $initials .= strtoupper(mb_substr($w, 0, 1));
            }
            $initials = $initials ?: 'SB';
        @endphp

        {{-- Right Utility: Home Link & User Avatar Bulat di Kanan Atas --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" target="_blank"
               class="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-zinc-500 hover:text-zinc-900 hover:bg-zinc-100 transition-colors">
                <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                <span>Lihat Galeri Publik</span>
            </a>

            <div class="relative" @click.outside="userDropdown = false">
                <button @click="userDropdown = !userDropdown" 
                        class="h-8 px-2.5 rounded-full bg-zinc-100 hover:bg-zinc-200/80 border border-zinc-200/80 text-zinc-900 flex items-center gap-2 transition focus:outline-none">
                    <span class="w-5 h-5 rounded-full bg-zinc-950 text-white font-mono-tag text-[10px] font-bold flex items-center justify-center">
                        {{ $initials }}
                    </span>
                    <span class="text-xs font-medium max-w-[100px] truncate hidden sm:inline-block">
                        {{ explode(' ', trim($name))[0] }}
                    </span>
                    <svg class="w-3 h-3 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                {{-- Dropdown Akun --}}
                <div x-show="userDropdown"
                     x-cloak
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-zinc-200/80 py-2 z-50">
                    <div class="px-4 py-2.5 border-b border-zinc-100">
                        <p class="text-[10px] font-mono-tag uppercase tracking-wider text-zinc-400">Signed in as</p>
                        <p class="text-xs font-bold text-zinc-900 truncate">{{ auth()->user()->name ?? 'User' }}</p>
                        <p class="text-[11px] text-zinc-500 truncate">{{ auth()->user()->email ?? '' }}</p>
                    </div>
                    <div class="py-1">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-xs text-zinc-700 hover:bg-zinc-50 transition-colors">
                            <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            Pengaturan Profil
                        </a>
                    </div>
                    <div class="border-t border-zinc-100 pt-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 font-medium transition-colors">
                                <svg class="w-3.5 h-3.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                Keluar (Logout)
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Layout Area Bawah Header --}}
    <div class="h-[calc(100vh-4rem)] flex overflow-hidden">
        
        {{-- Mobile Overlay --}}
        <div x-show="sidebarOpen" 
             x-cloak
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-gray-900/40 lg:hidden" 
             @click="sidebarOpen = false"></div>

        {{-- Sidebar Container --}}
        <div class="fixed inset-y-16 left-0 z-40 w-64 transform transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 flex-shrink-0"
             :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
            @include('layouts.sidebar.' . $role)
        </div>

        {{-- Konten Utama Halaman --}}
        <main class="flex-1 overflow-y-auto px-6 py-6 lg:px-10 lg:py-8 bg-[#FAFAFA]">
            @if (session('success'))
                <div class="mb-5 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-xl flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button type="button" @click="$el.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold">&times;</button>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-5 p-4 bg-rose-50 border border-rose-200 text-rose-800 text-sm rounded-xl flex items-center justify-between">
                    <span>{{ session('error') }}</span>
                    <button type="button" @click="$el.parentElement.remove()" class="text-rose-500 hover:text-rose-700 font-bold">&times;</button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
