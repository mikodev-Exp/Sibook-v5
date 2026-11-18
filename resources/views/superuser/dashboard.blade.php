@extends('layouts.role', ['role' => 'superuser'])

@section('title', 'Dashboard Super User')

@section('content')
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Pusat Kendali Sistem 🛡️</h2>
        <p class="text-gray-500 mt-1">Selamat datang, Super User. Di sini Anda memegang kendali penuh atas sistem.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Menunggu Approval -->
        <div class="bg-gradient-to-br from-indigo-600 to-purple-700 p-6 rounded-2xl shadow-lg text-white relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white opacity-10 rounded-full"></div>
            <p class="text-indigo-100 font-medium mb-1">Menunggu Persetujuan Akhir</p>
            <h4 class="text-4xl font-bold">{{ $menungguApproval }}</h4>
            <p class="text-sm mt-2 text-indigo-100">Pendaftar yang sudah di-review Admin</p>
        </div>

        <!-- Total Mahasiswa -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-gray-500 font-medium mb-1">Total Mahasiswa Aktif</p>
            <h4 class="text-4xl font-bold text-gray-800">{{ $totalMahasiswa }}</h4>
            <p class="text-sm mt-2 text-gray-400">Pengguna dengan role Mahasiswa</p>
        </div>

        <!-- Total Admin -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-gray-500 font-medium mb-1">Total Admin</p>
            <h4 class="text-4xl font-bold text-gray-800">{{ $totalAdmin }}</h4>
            <p class="text-sm mt-2 text-gray-400">Pengguna dengan role Admin</p>
        </div>
    </div>
@endsection