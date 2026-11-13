<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PresensiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $presensiHariIni = $user->presensis()->where('tanggal', today())->first();
        
        // Riwayat dengan filter (bisa dikembangkan nanti)
        $riwayat = $user->presensis()->orderBy('tanggal', 'desc')->paginate(10);

        // Ambil titik kantor
        $kantorLat = (float) Pengaturan::get('kantor.lat', -7.2863000);
        $kantorLng = (float) Pengaturan::get('kantor.lng', 112.7358000);
        $radius = (int) Pengaturan::get('kantor.radius_m', 1000);

        return view('mahasiswa.presensi.index', compact('presensiHariIni', 'riwayat', 'kantorLat', 'kantorLng', 'radius'));
    }

    public function storeMasuk(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $user = Auth::user();
        $hariIni = today();

        // Cek sudah absen atau belum
        if ($user->presensis()->where('tanggal', $hariIni)->exists()) {
            return back()->with('error', 'Anda sudah melakukan presensi masuk hari ini.');
        }

        // Hitung Jarak
        $kantorLat = (float) Pengaturan::get('kantor.lat');
        $kantorLng = (float) Pengaturan::get('kantor.lng');
        $radius = (int) Pengaturan::get('kantor.radius_m');
        $jarak = $this->hitungJarak($request->lat, $request->lng, $kantorLat, $kantorLng);

        if ($jarak > $radius) {
            return back()->with('error', "Anda berada di luar radius kantor. Jarak Anda: " . round($jarak) . " meter.");
        }

        // Cek Keterlambatan (Batas 07:30)
        $jamSekarang = now();
        $batasTerlambat = Carbon::createFromTimeString('07:30');
        $status = $jamSekarang->greaterThan($batasTerlambat) ? 'terlambat' : 'hadir';

        Presensi::create([
            'user_id' => $user->id,
            'tanggal' => $hariIni,
            'jam_masuk' => $jamSekarang->format('H:i:s'),
            'lat_masuk' => $request->lat,
            'lng_masuk' => $request->lng,
            'jarak_masuk_m' => $jarak,
            'status' => $status,
            'tipe_presensi' => 'kantor',
            'status_verifikasi' => 'approved',
        ]);

        return back()->with('success', 'Presensi masuk berhasil dicatat.');
    }

    public function storeKeluar(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $user = Auth::user();
        $presensi = $user->presensis()->where('tanggal', today())->first();

        if (!$presensi || !$presensi->jam_masuk) {
            return back()->with('error', 'Anda belum presensi masuk hari ini.');
        }

        // Cek Logbook
        $logbookHariIni = $user->logbooks()->where('tanggal', today())->exists();
        if (!$logbookHariIni) {
            return back()->with('error', 'Anda wajib mengisi Logbook sebelum presensi keluar.');
        }

        // Hitung Jarak
        // Angka di belakang koma adalah nilai default jika database kosong
        $kantorLat = (float) Pengaturan::get('kantor.lat', -7.2863000);
        $kantorLng = (float) Pengaturan::get('kantor.lng', 112.7358000);
        $radius = (int) Pengaturan::get('kantor.radius_m', 1000);
        $jarak = $this->hitungJarak($request->lat, $request->lng, $kantorLat, $kantorLng);

        if ($jarak > $radius) {
            return back()->with('error', "Anda berada di luar radius kantor. Jarak Anda: " . round($jarak) . " meter.");
        }

        // Cek Pulang Cepat (Senin-Kamis 16:00, Jumat 16:30)
        $jamSekarang = now();
        $hari = $jamSekarang->dayOfWeek; // 5 = Jumat, 6 = Sabtu, 0 = Minggu
        $batasPulang = ($hari === 5) ? '16:30' : '16:00';
        $minPulang = Carbon::createFromTimeString($batasPulang);
        
        $keterangan = 'Tepat Waktu';
        if ($jamSekarang->lessThan($minPulang)) {
            $keterangan = 'Pulang Terlalu Cepat';
        }

        $presensi->update([
            'jam_pulang' => $jamSekarang->format('H:i:s'),
            'lat_pulang' => $request->lat,
            'lng_pulang' => $request->lng,
            'jarak_pulang_m' => $jarak,
            'keterangan' => $keterangan
        ]);

        return back()->with('success', 'Presensi keluar berhasil dicatat.');
    }

    public function storeKamera(Request $request)
    {
        $request->validate([
            'foto' => 'required|string', // Base64 string
            'alasan' => 'required|string|max:500',
        ]);

        $user = Auth::user();
        $hariIni = today();

        if ($user->presensis()->where('tanggal', $hariIni)->exists()) {
            return back()->with('error', 'Anda sudah melakukan presensi hari ini.');
        }

        // Simpan Foto Base64
        $imageParts = explode(";base64,", $request->foto);
        $imageTypeAux = explode("image/", $imageParts[0]);
        $imageType = $imageTypeAux[1];
        $imageBase64 = base64_decode($imageParts[1]);
        $fileName = 'presensi/' . $user->id . '_' . time() . '.' . $imageType;
        
        Storage::disk('public')->put($fileName, $imageBase64);

        // Simpan Data sebagai pending (menunggu verifikasi admin)
        Presensi::create([
            'user_id' => $user->id,
            'tanggal' => $hariIni,
            'jam_masuk' => now()->format('H:i:s'),
            'status' => 'hadir',
            'tipe_presensi' => 'luar_kantor',
            'foto' => $fileName,
            'alasan_luar' => $request->alasan,
            'status_verifikasi' => 'pending',
        ]);

        return back()->with('success', 'Presensi luar kantor berhasil dikirim dan menunggu verifikasi Admin.');
    }

    // Fungsi Haversine
    private function hitungJarak($lat1, $lon1, $lat2, $lon2) {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return ($miles * 1.609344) * 1000; // Hasil dalam meter
    }
}