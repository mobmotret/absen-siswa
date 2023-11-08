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
        return view('presensi.create');
    }

    public function store(Request $request)
    {
        $nis = Auth::guard('siswa')->user()->nis;
        $tgl_presensi = date("Y-m-d");
        $jam = date("H:i:s");
        $lokasi = $request->lokasi;
        $image = $request->image;
        $folderPath = "public/uploads/absensi/";
        $formatName = $nis . "-" . $tgl_presensi;
        $image_part = explode(";base64", $image);
        $image_base64 = base64_decode($image_part[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;

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
