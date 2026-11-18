<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserRegisterController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->input('role');
        $query = User::query();

        if ($role && in_array($role, ['pendaftar', 'mahasiswa'])) {
            $query->role($role);
        } else {
            $query->role(['pendaftar', 'mahasiswa']);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        return view('admin.user-register.index', compact('users'));
    }
}