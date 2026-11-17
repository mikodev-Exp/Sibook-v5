<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use App\Services\ExportRekapService;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        $query = Presensi::with(['user.mahasiswaProfile']);

        if ($search = $request->input('search')) {
            $query->whereHas('user', function ($uq) use ($search) {
                $uq->where('name', 'like', "%{$search}%");
            });
        }

        if ($tanggal = $request->input('tanggal')) {
            $query->whereDate('tanggal', $tanggal);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $presensis = $query->orderBy('tanggal', 'desc')->orderBy('jam_masuk', 'desc')->paginate(15)->withQueryString();

        return view('admin.presensi.index', compact('presensis'));
    }

    public function export(Request $request, ExportRekapService $exportService)
    {
        $query = Presensi::with(['user.mahasiswaProfile']);

        if ($search = $request->input('search')) {
            $query->whereHas('user', function ($uq) use ($search) {
                $uq->where('name', 'like', "%{$search}%");
            });
        }

        if ($tanggal = $request->input('tanggal')) {
            $query->whereDate('tanggal', $tanggal);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $presensis = $query->orderBy('tanggal', 'desc')->orderBy('jam_masuk', 'desc')->get();

        return $exportService->exportPresensi($presensis);
    }
}
