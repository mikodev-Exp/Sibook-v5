@props([
    'alt' => 'Badan Pusat Statistik Kota Surabaya',
    'class' => 'h-10 w-auto object-contain',
])

<div {{ $attributes->merge(['class' => 'inline-flex items-center justify-center shrink-0']) }}>
    <img src="{{ asset('images/logo-bps.jpg') }}"
         alt="{{ $alt }}"
         class="{{ $class }}"
         loading="lazy"
         onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.classList.remove('hidden');" />

    {{-- Fallback SVG if image fails to load --}}
    <div class="hidden inline-flex items-center justify-center bg-indigo-50 border border-indigo-200 text-indigo-700 font-bold text-xs rounded-lg px-2.5 py-1">
        BPS
    </div>
</div>
