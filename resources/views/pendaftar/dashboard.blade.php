@extends('layouts.role', ['role' => 'pendaftar'])

@section('title', 'Dashboard Pendaftar')

@section('content')
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Halo, {{ auth()->user()->name }}! 👋</h2>
        <p class="text-gray-600 mb-6">
            Akun Anda terdaftar sebagai <strong>Pendaftar</strong>. Silakan lengkapi dokumen dan ajukan permohonan magang Anda.
        </p>
        
        <div class="p-4 bg-yellow-50 text-yellow-800 rounded-lg">
            Modul Pengajuan Magang akan segera hadir pada tahap pengembangan berikutnya.
        </div>
    </div>
@endsection