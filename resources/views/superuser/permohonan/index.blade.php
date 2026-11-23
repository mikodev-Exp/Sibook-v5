@extends('layouts.role', ['role' => 'superuser'])

@section('title', 'Finalisasi Permohonan Magang - Super User')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    {{-- Alerts --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-xs font-mono-tag shadow-xs">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center gap-3 text-xs font-mono-tag shadow-xs">
            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Header Placard --}}
    <div class="bg-white rounded-3xl border border-zinc-200/80 p-8 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 text-xs font-mono-tag text-zinc-400 uppercase tracking-widest mb-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>FINAL REVIEW PROTOCOL // ROOT APPROVAL</span>
            </div>
            <h1 class="text-3xl font-extrabold text-zinc-950 tracking-tight">Finalisasi Pendaftar Magang</h1>
            <p class="text-xs text-zinc-500 mt-1 max-w-2xl leading-relaxed">
                Hanya permohonan yang telah diverifikasi kelayakannya oleh Admin yang masuk ke antrean Super Admin untuk persetujuan resmi dan pengaktifan akun Mahasiswa Magang.
            </p>
        </div>

        <div class="flex items-center gap-2 font-mono-tag text-xs text-zinc-500">
            <div class="bg-zinc-50 border border-zinc-200/80 rounded-2xl px-4 py-2 text-right">
                <span class="text-[10px] text-zinc-400 block">SIAP DISETUJUI</span>
                <span class="text-xl font-bold text-zinc-950">{{ $countMenunggu }}</span>
            </div>
        </div>
    </div>

    {{-- Tabs & Search Toolbar --}}
    <div class="bg-white rounded-3xl border border-zinc-200/80 p-5 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            {{-- Filter Tabs --}}
            <div class="flex flex-wrap items-center gap-2 font-mono-tag text-xs">
                <a href="{{ route('superuser.permohonan.index', ['tab' => 'menunggu']) }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl transition-all {{ $tab === 'menunggu' ? 'bg-zinc-950 text-white font-bold shadow-xs' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200' }}">
                    <span>Perlu Persetujuan</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $tab === 'menunggu' ? 'bg-amber-400 text-zinc-950' : 'bg-zinc-200 text-zinc-700' }}">{{ $countMenunggu }}</span>
                </a>
                <a href="{{ route('superuser.permohonan.index', ['tab' => 'approved']) }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl transition-all {{ $tab === 'approved' ? 'bg-zinc-950 text-white font-bold shadow-xs' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200' }}">
                    <span>Telah Disetujui</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $tab === 'approved' ? 'bg-emerald-400 text-zinc-950' : 'bg-zinc-200 text-zinc-700' }}">{{ $countApproved }}</span>
                </a>
                <a href="{{ route('superuser.permohonan.index', ['tab' => 'rejected']) }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl transition-all {{ $tab === 'rejected' ? 'bg-zinc-950 text-white font-bold shadow-xs' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200' }}">
                    <span>Ditolak</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $tab === 'rejected' ? 'bg-rose-400 text-white' : 'bg-zinc-200 text-zinc-700' }}">{{ $countRejected }}</span>
                </a>
                <a href="{{ route('superuser.permohonan.index', ['tab' => 'all']) }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl transition-all {{ $tab === 'all' ? 'bg-zinc-950 text-white font-bold shadow-xs' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200' }}">
                    <span>Semua Riwayat</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $tab === 'all' ? 'bg-zinc-700 text-white' : 'bg-zinc-200 text-zinc-700' }}">{{ $countTotal }}</span>
                </a>
            </div>

            {{-- Search Bar --}}
            <form method="GET" action="{{ route('superuser.permohonan.index') }}" class="w-full sm:w-72">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari nama, email, kampus..." 
                           class="w-full pl-9 pr-4 py-2 rounded-xl border border-zinc-200 text-xs text-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-950 focus:border-zinc-950 transition">
                    <svg class="w-4 h-4 text-zinc-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </form>
        </div>

        {{-- Explanatory Note on Hierarchy --}}
        <div class="p-3.5 rounded-2xl bg-zinc-50 border border-zinc-200/60 flex items-start gap-2.5 text-xs text-zinc-600 font-mono-tag">
            <svg class="w-4 h-4 text-zinc-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="leading-relaxed">
                <strong>Catatan Alur:</strong> Pendaftar baru berstatus <em>Menunggu Review Admin</em> berada pada ranah verifikasi berkas oleh Admin. Pendaftar baru diteruskan ke Super Admin setelah dinyatakan <strong>LAYAK</strong> oleh Admin.
            </p>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-3xl border border-zinc-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="text-[11px] font-mono-tag text-zinc-400 uppercase tracking-wider bg-zinc-50/70 border-b border-zinc-100">
                    <tr>
                        <th class="px-6 py-4">Pendaftar &amp; Akademik</th>
                        <th class="px-6 py-4">Periode Rencana</th>
                        <th class="px-6 py-4">Tinjauan Admin</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse($permohonan as $row)
                    <tr class="hover:bg-zinc-50/60 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-zinc-900 text-sm">{{ $row->user->name }}</div>
                            <div class="text-zinc-500 font-mono-tag mt-0.5">{{ $row->user->mahasiswaProfile->universitas ?? '-' }}</div>
                            <div class="text-zinc-400 font-mono-tag text-[11px]">{{ $row->user->email }} • {{ $row->user->mahasiswaProfile->program_studi ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 font-mono-tag">
                            <div class="font-bold text-zinc-800">{{ \Carbon\Carbon::parse($row->tanggal_mulai_rencana)->translatedFormat('d M Y') }}</div>
                            <div class="text-zinc-400 text-[10px]">s/d {{ \Carbon\Carbon::parse($row->tanggal_selesai_rencana)->translatedFormat('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4 font-mono-tag max-w-xs">
                            @if($row->reviewer)
                                <div class="text-zinc-800 font-semibold">{{ $row->reviewer->name }}</div>
                                <div class="text-[10px] text-zinc-400 truncate" title="{{ $row->catatan_admin }}">{{ $row->catatan_admin ?: 'Tanpa catatan' }}</div>
                            @else
                                <span class="text-zinc-400 italic text-[11px]">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-mono-tag">
                            @if($row->status === \App\Enums\StatusPermohonan::ReviewedByAdmin)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                    Siap di-Approve
                                </span>
                            @elseif($row->status === \App\Enums\StatusPermohonan::Approved)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Disetujui
                                </span>
                            @elseif($row->status === \App\Enums\StatusPermohonan::Rejected)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Ditolak
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-zinc-100 text-zinc-600 border border-zinc-200">
                                    {{ $row->status->label() }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('superuser.permohonan.show', $row->id) }}" 
                               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-semibold font-mono-tag transition shadow-xs">
                                <span>Detail &amp; Finalisasi</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-zinc-400 font-mono-tag">
                            <div class="max-w-sm mx-auto space-y-2">
                                <svg class="w-8 h-8 text-zinc-300 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p class="text-xs">Tidak ada permohonan dalam antrean tab ini.</p>
                                @if($tab === 'menunggu')
                                    <p class="text-[11px] text-zinc-400">Permohonan yang masih menunggu review staf Admin belum masuk ke sini.</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($permohonan->hasPages())
            <div class="p-5 border-t border-zinc-100">
                {{ $permohonan->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
