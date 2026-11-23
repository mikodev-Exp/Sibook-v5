@extends('layouts.role', ['role' => 'mahasiswa'])

@section('title', 'Perizinan Magang')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">

    {{-- Header Halaman --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 text-xs font-mono-tag text-zinc-400 uppercase tracking-widest mb-1.5">
                <span>PERMISSION PROTOCOL</span>
            </div>
            <h1 class="text-3xl font-extrabold text-zinc-950 tracking-tight">Perizinan &amp; Dispensasi</h1>
            <p class="text-xs text-zinc-500 mt-1">Ajukan permohonan izin atau sakit serta pantau status kurasi persetujuannya.</p>
        </div>

        {{-- Stat Chips --}}
        <div class="flex flex-wrap items-center gap-2.5 font-mono-tag text-xs">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white text-zinc-800 border border-zinc-200 shadow-xs">
                <span class="font-bold text-zinc-950">{{ $totalIzin }}</span> TOTAL PENGAJUAN
            </span>
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                <span class="font-bold">{{ $disetujui }}</span> APPROVED
            </span>
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                <span class="font-bold">{{ $menunggu }}</span> PENDING
            </span>
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                <span class="font-bold">{{ $ditolak }}</span> REJECTED
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Form Pengajuan Izin --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl border border-zinc-200/80 p-7 shadow-sm sticky top-20">
                <div class="flex items-center justify-between pb-4 mb-5 border-b border-zinc-100">
                    <div>
                        <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-widest block mb-0.5">REQUEST FORM</span>
                        <h2 class="text-base font-extrabold text-zinc-950 tracking-tight">Ajukan Dispensasi</h2>
                    </div>
                    <span class="text-xs font-mono-tag text-zinc-400">[NEW PASS]</span>
                </div>

                <form action="{{ route('mahasiswa.perizinan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-mono-tag uppercase text-zinc-600 mb-1.5">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', date('Y-m-d')) }}" required
                               class="w-full text-xs font-mono-tag border border-zinc-200 rounded-xl px-3.5 py-2.5 text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        @error('tanggal_mulai')
                            <p class="text-[11px] text-rose-600 mt-1 font-mono-tag">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-mono-tag uppercase text-zinc-600 mb-1.5">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', date('Y-m-d')) }}" required
                               class="w-full text-xs font-mono-tag border border-zinc-200 rounded-xl px-3.5 py-2.5 text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        @error('tanggal_selesai')
                            <p class="text-[11px] text-rose-600 mt-1 font-mono-tag">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-mono-tag uppercase text-zinc-600 mb-1.5">Alasan Permohonan</label>
                        <textarea name="alasan" rows="3" required placeholder="Jelaskan alasan izin / sakit secara rinci..."
                                  class="w-full text-xs border border-zinc-200 rounded-xl p-3.5 text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-950">{{ old('alasan') }}</textarea>
                        @error('alasan')
                            <p class="text-[11px] text-rose-600 mt-1 font-mono-tag">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-mono-tag uppercase text-zinc-600 mb-1.5">Unggah Surat Bukti (PDF / Foto)</label>
                        <input type="file" name="bukti_file" accept=".pdf,.jpg,.jpeg,.png"
                               class="w-full text-xs text-zinc-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-mono-tag file:font-semibold file:bg-zinc-100 file:text-zinc-800 hover:file:bg-zinc-200 cursor-pointer">
                        <p class="text-[11px] font-mono-tag text-zinc-400 mt-1">MAKS. 5MB (PDF, PNG, JPG)</p>
                        @error('bukti_file')
                            <p class="text-[11px] text-rose-600 mt-1 font-mono-tag">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-1">
                        <label class="block text-xs font-mono-tag uppercase text-zinc-600 mb-1.5">Atau Tautan Dokumen Eksternal</label>
                        <input type="url" name="url_bukti" value="{{ old('url_bukti') }}" placeholder="https://drive.google.com/..."
                               class="w-full text-xs font-mono-tag border border-zinc-200 rounded-xl px-3.5 py-2.5 text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        @error('url_bukti')
                            <p class="text-[11px] text-rose-600 mt-1 font-mono-tag">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="w-full py-3 px-4 bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-mono-tag font-semibold rounded-xl transition shadow-sm hover:shadow">
                        KIRIM PENGAJUAN IZIN
                    </button>
                </form>
            </div>
        </div>

        {{-- Tabel Riwayat Izin --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl border border-zinc-200/80 shadow-sm overflow-hidden flex flex-col min-h-[500px]">
                <div class="p-6 sm:p-8 border-b border-zinc-100 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-mono-tag text-zinc-400 uppercase tracking-widest block mb-0.5">PERMISSION LEDGER</span>
                        <h2 class="text-base font-extrabold text-zinc-950 tracking-tight">Riwayat Pengajuan Dispensasi</h2>
                    </div>
                    <span class="text-xs font-mono-tag text-zinc-400">STATUS LOG</span>
                </div>

                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse text-xs font-mono-tag">
                        <thead>
                            <tr class="border-b border-zinc-100 text-zinc-400 uppercase tracking-wider bg-zinc-50/50">
                                <th class="py-3.5 px-6">Periode</th>
                                <th class="py-3.5 px-4">Alasan</th>
                                <th class="py-3.5 px-3">Bukti</th>
                                <th class="py-3.5 px-6 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 text-zinc-800 font-sans">
                            @forelse($perizinans as $item)
                                @php
                                    $status = $item->status?->value ?? (string) $item->status;
                                    $badge = match($status) {
                                        'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        default => 'bg-amber-50 text-amber-700 border-amber-200',
                                    };
                                    $label = match($status) {
                                        'approved' => 'APPROVED',
                                        'rejected' => 'REJECTED',
                                        default => 'PENDING',
                                    };
                                @endphp
                                <tr class="hover:bg-zinc-50/60 transition">
                                    <td class="py-4 px-6 whitespace-nowrap font-mono-tag">
                                        <div class="font-bold text-zinc-950 text-xs">
                                            {{ \Carbon\Carbon::parse($item->tanggal_mulai)->isoFormat('D MMM Y') }}
                                        </div>
                                        <div class="text-[11px] text-zinc-400 mt-0.5">
                                            s/d {{ \Carbon\Carbon::parse($item->tanggal_selesai)->isoFormat('D MMM Y') }}
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 max-w-xs">
                                        <p class="text-xs text-zinc-800 line-clamp-2 leading-relaxed" title="{{ $item->alasan }}">{{ $item->alasan }}</p>
                                        @if($item->catatan)
                                            <p class="text-[11px] font-mono-tag text-zinc-400 mt-1 italic">Catatan: {{ $item->catatan }}</p>
                                        @endif
                                    </td>
                                    <td class="py-4 px-3 whitespace-nowrap font-mono-tag">
                                        @if($item->url_bukti)
                                            <a href="{{ $item->url_bukti }}" target="_blank"
                                               class="text-xs font-semibold text-zinc-950 hover:underline inline-flex items-center gap-1">
                                                <span>BUKTI</span>
                                                <svg class="w-3 h-3 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                </svg>
                                            </a>
                                        @else
                                            <span class="text-xs text-zinc-300">-</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right whitespace-nowrap font-mono-tag">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold border {{ $badge }}">
                                            {{ $label }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-14 text-center">
                                        <div class="flex flex-col items-center justify-center text-zinc-400 font-mono-tag">
                                            <p class="text-xs font-bold text-zinc-600">// BELUM ADA PENGAJUAN IZIN</p>
                                            <p class="text-[11px] text-zinc-400 mt-1">Ajukan permohonan izin melalui formulir di samping.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($perizinans->hasPages())
                    <div class="p-5 border-t border-zinc-100 mt-auto bg-zinc-50/50">
                        {{ $perizinans->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>

</div>
@endsection
