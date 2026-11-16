<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserRegisterController extends Controller
{
    public function index()
    {
        $users = User::role(['pendaftar', 'mahasiswa'])->latest()->paginate(10);
        return view('admin.user-register.index', compact('users'));
    }
}