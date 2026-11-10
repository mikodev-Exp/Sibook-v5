<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardRedirectController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $user = $request->user();

        $route = match (true) {
            $user->hasRole('super_user') => 'superuser.dashboard',
            $user->hasRole('admin')      => 'admin.dashboard',
            $user->hasRole('mahasiswa')  => 'mahasiswa.dashboard',
            $user->hasRole('pendaftar')  => 'pendaftar.dashboard',
            default                      => null,
        };

        if ($route === null) {
            abort(403, 'Role akun Anda tidak dikenali. Hubungi administrator.');
        }

        return redirect()->route($route);
    }
}