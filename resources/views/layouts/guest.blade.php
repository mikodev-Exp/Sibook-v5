<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SIBOOK') }} - Portal Akses Terpadu</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800|space-mono:400,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Figtree', sans-serif; }
        .font-mono-tag { font-family: 'Space Mono', monospace; }
    </style>
</head>
<body class="h-full bg-[#FAFAFA] text-[#09090B] antialiased flex flex-col justify-between selection:bg-zinc-900 selection:text-white">
    
    <!-- Top Minimalist Bar -->
    <header class="py-5 px-6 sm:px-10 flex items-center justify-between border-b border-zinc-200/80 bg-white/70 backdrop-blur-md">
        <a href="{{ route('home') }}" class="flex items-center gap-3 group">
            <div class="w-8 h-8 rounded-lg bg-zinc-950 text-white flex items-center justify-center font-bold text-xs tracking-tighter shadow-sm group-hover:scale-105 transition-transform">
                SB
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-base font-extrabold tracking-tight text-zinc-950">SiBook</span>
                <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-widest hidden sm:inline-block">BPS SURABAYA</span>
            </div>
        </a>
        <a href="{{ route('home') }}" class="text-xs font-mono-tag text-zinc-500 hover:text-zinc-950 transition-colors flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Beranda</span>
        </a>
    </header>

    <!-- Main Content Area: Exhibition Placard Style -->
    <main class="flex-1 flex items-center justify-center py-12 px-4 sm:px-6">
        <div class="w-full sm:max-w-md">
            {{ $slot }}
        </div>
    </main>

    <!-- Footer Note -->
    <footer class="py-4 text-center text-xs font-mono-tag text-zinc-400 border-t border-zinc-200/60">
        &copy; {{ date('Y') }} Badan Pusat Statistik Kota Surabaya • SIBOOK-V5
    </footer>
</body>
</html>
