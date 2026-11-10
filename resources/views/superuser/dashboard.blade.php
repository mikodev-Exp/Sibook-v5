@extends('layouts.role', ['role' => 'superuser'])

@section('title', 'Dashboard Super User')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-bold mb-2">Selamat datang, {{ auth()->user()->name }}</h2>
        <p class="text-gray-600">
            Anda login sebagai <strong>super user</strong>. Modul approval permohonan dan pengaturan
            sistem akan tersedia pada tahap pengembangan berikutnya.
        </p>
    </div>
@endsection