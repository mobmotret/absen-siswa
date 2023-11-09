<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PresensiController extends Controller
{
    public function create()
    {
        $latDepartment = "-6.97924571609778";
        $longDepartment = "107.67349137814192";
        $hariini = date("Y-m-d");
        $nis = Auth::guard('siswa')->user()->nis;
        $cek = DB::table('presensi')->where('tgl_presensi', $hariini)->where('nis', $nis)->count();
        return view('presensi.create', compact('cek', 'latDepartment', 'longDepartment'));
    }

    public function store(Request $request)
    {

        $nis = Auth::guard('siswa')->user()->nis;
        $tgl_presensi = date("Y-m-d");
        $jam = date("H:i:s");
        $lokasi = $request->lokasi;
        $image = $request->image;
        $folderPath = "public/uploads/absensi/";
        $cek = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nis', $nis)->count();

        if ($cek > 0) {
            $ket = 'out';
        } else {
            $ket = 'in';
        }

        $formatName = $nis . "-" . $tgl_presensi . "-" . $ket;

        $image_part = explode(";base64", $image);
        $image_base64 = base64_decode($image_part[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;

        // $locDepartment = explode(',', '-6.977825313137006,107.67349137814192'); //koordinta inovindo
        $locDepartment = explode(',', $lokasi); //koordinta inovindo
        $locUser = explode(',', $lokasi);


        if ($cek > 0) {
            $data_pulang = [
                'jam_out' => $jam,
                'foto_out' => $fileName,
                'location_out' => $lokasi,
            ];
            $update = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nis', $nis)->update($data_pulang);

            if ($update) {
                Storage::put($file, $image_base64);
                return response()->json([
                    'status' => true,
                    'message' => 'Sudah Presensi Pulang.'
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan di server.'
                ], 500);
            }
        } else {

            $jarak = $this->haversineDistance($locDepartment[0], $locDepartment[1], $locUser[0], $locUser[1]);
            if ($jarak > 500) {
                return response()->json([
                    'status' => false,
                    'message' => 'Anda di luar radius.'
                ], 500);
            }

            $data = [
                'nis' => $nis,
                'tgl_presensi' => $tgl_presensi,
                'jam_in' => $jam,
                'foto_in' => $fileName,
                'location_in' => $lokasi,
            ];

            $simpan = DB::table('presensi')->insert($data);
            if ($simpan) {
                Storage::put($file, $image_base64);
                return response()->json([
                    'status' => true,
                    'message' => 'Sudah Presensi.'
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan di server.'
                ], 500);
            }
        }
    }

    public function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        // Radius bumi dalam kilometer
        $earthRadius = 6371;

        // Mengubah sudut menjadi radian
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        // Selisih sudut antara kedua titik koordinat
        $latDiff = $lat2 - $lat1;
        $lonDiff = $lon2 - $lon1;

        // Menghitung jarak menggunakan rumus Haversine
        $a = sin($latDiff / 2) * sin($latDiff / 2) + cos($lat1) * cos($lat2) * sin($lonDiff / 2) * sin($lonDiff / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return $distance * 1000;
    }
}
