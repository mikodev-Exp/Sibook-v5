@extends('layouts.role', ['role' => 'mahasiswa'])

@section('title', 'Logbook Harian')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Peringatan ganda dihapus dari sini karena sudah ditangani oleh layouts/role.blade.php -->

    <!-- Container 1: Info & Aksi -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 text-white flex flex-col md:flex-row justify-between items-center shadow-lg">
        <div>
            <h2 class="text-2xl font-bold mb-1">Logbook Magang</h2>
            <p class="text-indigo-100">Catat kegiatan harian Anda di sini.</p>
            <div class="flex gap-4 mt-6 text-sm font-medium">
                <div class="bg-white/20 px-4 py-2 rounded-lg border border-white/30">Total: {{ $totalLogbook }}</div>
                <div class="bg-green-500/30 px-4 py-2 rounded-lg border border-green-300/30">Selesai: {{ $selesai }}</div>
                <div class="bg-orange-500/30 px-4 py-2 rounded-lg border border-orange-300/30">Draf: {{ $belumSelesai }}</div>
            </div>
        </div>
        <div class="flex gap-3 mt-6 md:mt-0">
            <a href="{{ route('mahasiswa.logbook.lanjutkan') }}" class="px-6 py-3 bg-white text-indigo-700 font-bold rounded-xl hover:bg-gray-100 transition-colors shadow-sm">
                Lanjutkan Logbook
            </a>
            <a href="{{ route('mahasiswa.logbook.create') }}" class="px-6 py-3 bg-indigo-800 text-white font-bold rounded-xl hover:bg-indigo-900 transition-colors shadow-sm">
                Buat Logbook
            </a>
        </div>
    </div>

    <!-- Container 2: Riwayat Logbook -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Riwayat Logbook</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-4">Tanggal & Waktu</th>
                        <th class="px-6 py-4">Deskripsi Logbook</th>
                        <th class="px-6 py-4">Bukti</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $row)
                    <!-- 1. BARIS INDUK (LOG BOOK UTAMA) -->
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $row->jam_mulai }} - {{ $row->jam_selesai ?? '...' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">{{ $row->tim }} - {{ $row->jenis_gsbpm }}</div>
                            <div class="text-sm text-gray-600 truncate max-w-xs">{{ $row->deskripsi }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ $row->link_bukti }}" target="_blank" class="text-indigo-600 hover:underline text-xs font-semibold">Lihat Bukti</a>
                        </td>
                        <td class="px-6 py-4">
                            @if($row->status === \App\Enums\StatusLogbook::Draft)
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">Belum Selesai</span>
                            @elseif($row->status === \App\Enums\StatusLogbook::Submitted)
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Selesai</span>
                            @elseif($row->status === \App\Enums\StatusLogbook::Approved)
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Disetujui Admin</span>
                            @elseif($row->status === \App\Enums\StatusLogbook::Rejected)
                                 <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center space-x-2">
                            <a href="{{ route('mahasiswa.logbook.edit', $row->id) }}" class="inline-block px-3 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 text-xs font-bold">Edit</a>
                            <form action="{{ route('mahasiswa.logbook.destroy', $row->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus logbook ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 text-xs font-bold">Hapus</button>
                            </form>
                        </td>
                    </tr>

                    <!-- 2. BARIS ANAK (LOG BOOK LANJUTAN) -->
                    @foreach($row->children as $child)
                    <tr class="bg-gray-50 border-b hover:bg-gray-100">
                        <td class="pl-12 pr-6 py-4 relative">
                            <!-- Ikon Panah Relasi -->
                            <div class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                            </div>
                            <div class="font-bold text-gray-700">{{ \Carbon\Carbon::parse($child->tanggal)->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $child->jam_mulai }} - {{ $child->jam_selesai ?? '...' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-700">{{ $child->tim }} - {{ $child->jenis_gsbpm }}</div>
                            <div class="text-sm text-gray-500 truncate max-w-xs">{{ $child->deskripsi }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ $child->link_bukti }}" target="_blank" class="text-indigo-600 hover:underline text-xs font-semibold">Lihat Bukti</a>
                        </td>
                        <td class="px-6 py-4">
                            @if($child->status === \App\Enums\StatusLogbook::Draft)
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">Belum Selesai</span>
                            @elseif($child->status === \App\Enums\StatusLogbook::Submitted)
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Selesai</span>
                            @elseif($child->status === \App\Enums\StatusLogbook::Approved)
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Disetujui Admin</span>
                            @elseif($child->status === \App\Enums\StatusLogbook::Rejected)
                                 <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center space-x-2">
                            <a href="{{ route('mahasiswa.logbook.edit', $child->id) }}" class="inline-block px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-xs font-bold">Edit</a>
                            <form action="{{ route('mahasiswa.logbook.destroy', $child->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus logbook ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 text-xs font-bold">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    <!-- END BARIS ANAK -->

                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada riwayat logbook.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $riwayat->links() }}
        </div>
    </div>
</div>
@endsection