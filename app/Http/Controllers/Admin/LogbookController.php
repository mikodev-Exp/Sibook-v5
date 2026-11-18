<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use Illuminate\Http\Request;

class LogbookController extends Controller
{
    public function index(Request $request)
    {
        $query = Logbook::with(['user.mahasiswaProfile']);

        // Search text: Nama mahasiswa, kegiatan, tim, deskripsi
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%");
                })
                ->orWhere('kegiatan', 'like', "%{$search}%")
                ->orWhere('deskripsi', 'like', "%{$search}%")
                ->orWhere('tim', 'like', "%{$search}%");
            });
        }

        // Filter Tim / Divisi
        if ($tim = $request->input('tim')) {
            if ($tim === 'Kegiatan Lainnya') {
                $query->where(function ($q) {
                    $q->whereNull('tim')->orWhere('tim', 'Kegiatan Lainnya')->orWhere('tim', '');
                });
            } else {
                $query->where('tim', $tim);
            }
        }

        // Filter Tanggal
        if ($tanggal = $request->input('tanggal')) {
            $query->whereDate('tanggal', $tanggal);
        }

        // Filter Status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Sorting
        $sort = $request->input('sort', 'tanggal');
        $direction = strtolower($request->input('direction', 'desc')) === 'asc' ? 'asc' : 'desc';

        if ($sort === 'nama') {
            $query->join('users', 'logbooks.user_id', '=', 'users.id')
                  ->orderBy('users.name', $direction)
                  ->select('logbooks.*');
        } elseif (in_array($sort, ['kegiatan', 'tim', 'tanggal', 'status'])) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('tanggal', 'desc')->orderBy('id', 'desc');
        }

        $logbooks = $query->paginate(15)->withQueryString();

        // Unique Tim / Divisi list for filters
        $availableTims = ['Neraca', 'IPDS', 'Humas', 'Umum', 'Sosial', 'PSS', 'Kegiatan Lainnya'];

        // Count active filters (excluding search & sort)
        $activeFiltersCount = 0;
        if ($request->filled('tim')) $activeFiltersCount++;
        if ($request->filled('tanggal')) $activeFiltersCount++;
        if ($request->filled('status')) $activeFiltersCount++;

        return view('admin.logbook.index', compact('logbooks', 'availableTims', 'activeFiltersCount'));
    }

    public function show(Logbook $logbook)
    {
        $logbook->load(['user.mahasiswaProfile', 'verifikator', 'parent', 'children']);
        return response()->json($logbook);
    }

    public function verify(Request $request, Logbook $logbook)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'catatan_verifikator' => 'nullable|string|max:1000',
        ]);

        $logbook->update([
            'status' => $validated['status'],
            'catatan_verifikator' => $validated['catatan_verifikator'] ?? null,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Status logbook berhasil diperbarui.');
    }

    public function export(Request $request, \App\Services\ExportRekapService $exportService)
    {
        $query = Logbook::with(['user.mahasiswaProfile']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%");
                })
                ->orWhere('kegiatan', 'like', "%{$search}%")
                ->orWhere('deskripsi', 'like', "%{$search}%")
                ->orWhere('tim', 'like', "%{$search}%");
            });
        }

        if ($tim = $request->input('tim')) {
            if ($tim === 'Kegiatan Lainnya') {
                $query->where(function ($q) {
                    $q->whereNull('tim')->orWhere('tim', 'Kegiatan Lainnya')->orWhere('tim', '');
                });
            } else {
                $query->where('tim', $tim);
            }
        }

        if ($tanggal = $request->input('tanggal')) {
            $query->whereDate('tanggal', $tanggal);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $logbooks = $query->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->get();

        return $exportService->exportLogbook($logbooks);
    }
}
