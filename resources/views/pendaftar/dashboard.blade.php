@extends('layouts.role', ['role' => 'pendaftar'])

@section('title', 'Status Pendaftaran Magang')

@section('content')
    @php
        $user = auth()->user();
        $permohonan = $user->permohonanMagang; // Mengambil permohonan terbaru
    @endphp

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Status Pendaftaran</h2>
                    <p class="text-sm text-gray-500 mt-1">Pantau status pengajuan magang Anda di sini.</p>
                </div>
                <div>
                    @if($permohonan)
                        @if($permohonan->status === 'submitted')
                            <span class="px-4 py-2 rounded-full text-sm font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">Menunggu Review Admin</span>
                        @elseif($permohonan->status === 'reviewed_by_admin')
                            <span class="px-4 py-2 rounded-full text-sm font-bold bg-blue-100 text-blue-800 border border-blue-200">Menunggu Persetujuan Super User</span>
                        @elseif($permohonan->status === 'approved')
                            <span class="px-4 py-2 rounded-full text-sm font-bold bg-green-100 text-green-800 border border-green-200">Disetujui (Magang Aktif)</span>
                        @elseif($permohonan->status === 'rejected')
                            <span class="px-4 py-2 rounded-full text-sm font-bold bg-red-100 text-red-800 border border-red-200">Ditolak</span>
                        @endif
                    @endif
                </div>
            </div>

            <div class="p-6">
                @if(!$permohonan)
                    <div class="text-center py-10">
                        <p class="text-gray-500 mb-4">Anda belum mengajukan permohonan magang.</p>
                        <a href="{{ route('pendaftar.register') }}" class="px-6 py-2 bg-indigo-600 text-white rounded-lg">Ajukan Sekarang</a>
                    </div>
                @else
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Data Diri -->
                        <div>
                            <h3 class="font-bold text-gray-800 mb-3 border-b pb-2">Data Diri</h3>
                            <ul class="space-y-2 text-sm">
                                <li class="flex justify-between"><span class="text-gray-500">Nama Lengkap</span> <span class="font-medium text-right">{{ $user->name }}</span></li>
                                <li class="flex justify-between"><span class="text-gray-500">Email</span> <span class="font-medium text-right">{{ $user->email }}</span></li>
                                <li class="flex justify-between"><span class="text-gray-500">No. HP</span> <span class="font-medium text-right">{{ $user->mahasiswaProfile->no_hp ?? '-' }}</span></li>
                                <li class="flex justify-between"><span class="text-gray-500">Universitas</span> <span class="font-medium text-right">{{ $user->mahasiswaProfile->universitas ?? '-' }}</span></li>
                                <li class="flex justify-between"><span class="text-gray-500">Universitas</span> <span class="font-medium text-right">{{ $user->mahasiswaProfile->universitas ?? '-' }}</span></li>
                                <li class="flex justify-between"><span class="text-gray-500">Instagram</span> <span class="font-medium text-right">{{ $user->mahasiswaProfile->username_ig ?? '-' }}</span></li>
                            </ul>
                        </div>

                        <!-- Detail Magang -->
                        <div>
                            <h3 class="font-bold text-gray-800 mb-3 border-b pb-2">Detail Pengajuan</h3>
                            <ul class="space-y-2 text-sm">
                                <li class="flex justify-between"><span class="text-gray-500">Tanggal Mulai</span> <span class="font-medium text-right">{{ \Carbon\Carbon::parse($permohonan->tanggal_mulai_rencana)->format('d M Y') }}</span></li>
                                <li class="flex justify-between"><span class="text-gray-500">Tanggal Selesai</span> <span class="font-medium text-right">{{ \Carbon\Carbon::parse($permohonan->tanggal_selesai_rencana)->format('d M Y') }}</span></li>
                                <li class="flex justify-between"><span class="text-gray-500">Status Konversi</span> <span class="font-medium text-right">{{ $user->mahasiswaProfile->status_konversi ? 'Ya' : 'Tidak' }}</span></li>
                                <li class="flex justify-between"><span class="text-gray-500">Device</span> <span class="font-medium text-right">{{ $user->mahasiswaProfile->device ?? '-' }}</span></li>
                                <li class="flex justify-between"><span class="text-gray-500">BPJS</span> <span class="font-medium text-right">{{ $user->mahasiswaProfile->punya_bpjs ? 'Ya' : 'Tidak' }}</span></li>
                            </ul>
                        </div>
                    </div>

                    @if($permohonan->status === 'submitted' || $permohonan->status === 'reviewed_by_admin')
                        <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-xl flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <p class="text-sm font-semibold text-blue-800">Mohon Tunggu</p>
                                <p class="text-xs text-blue-600 mt-1">Pendaftaran Anda sedang dalam proses peninjauan. Anda akan menerima email atau notifikasi jika status berubah.</p>
                            </div>
                        </div>
                    @elseif($permohonan->status === 'rejected')
                        <div class="mt-8 p-4 bg-red-50 border border-red-200 rounded-xl">
                            <p class="text-sm font-semibold text-red-800">Pendaftaran Ditolak</p>
                            <p class="text-xs text-red-600 mt-1">Alasan: {{ $permohonan->catatan_admin ?? 'Tidak ada catatan.' }}</p>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection