@extends('layouts.role', ['role' => 'superuser'])

@section('title', 'Finalisasi Magang')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    @if(session('success'))
        <div class="p-4 bg-green-100 border border-green-300 text-green-800 rounded-xl">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-100 border border-red-300 text-red-800 rounded-xl">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Daftar Pendaftar Magang</h2>
                <p class="text-sm text-gray-500 mt-1">Tinjau data pendaftar dan berikan keputusan akhir.</p>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-4">Nama & Akademik</th>
                        <th class="px-6 py-4">Periode Magang</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permohonan as $row)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $row->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $row->user->mahasiswaProfile->universitas ?? '-' }}</div>
                            <div class="text-xs text-gray-400">{{ $row->user->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium">{{ \Carbon\Carbon::parse($row->tanggal_mulai_rencana)->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500">s/d {{ \Carbon\Carbon::parse($row->tanggal_selesai_rencana)->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($row->status === \App\Enums\StatusPermohonan::Submitted)
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Menunggu Admin</span>
                            @elseif($row->status === \App\Enums\StatusPermohonan::ReviewedByAdmin)
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Siap di-Approve</span>
                            @elseif($row->status === \App\Enums\StatusPermohonan::Approved)
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Disetujui</span>
                            @elseif($row->status === \App\Enums\StatusPermohonan::Rejected)
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('superuser.permohonan.show', $row->id) }}" class="px-4 py-2 bg-indigo-50 text-indigo-600 font-bold rounded-lg hover:bg-indigo-100 transition-colors">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada data pendaftar magang.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $permohonan->links() }}
        </div>
    </div>
</div>
@endsection