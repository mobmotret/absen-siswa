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
        $hariini = date("Y-m-d");
        $nis = Auth::guard('siswa')->user()->nis;
        $cek = DB::table('presensi')->where('tgl_presensi', $hariini)->where('nis',$nis)->count();
        return view('presensi.create', compact('cek'));
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



        $cek = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nis',$nis)->count();
        if ($cek > 0){
            $data_pulang = [
                'jam_out' => $jam,
                'foto_out' => $fileName,
                'location_out' => $lokasi,
            ];
            $update = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nis',$nis)->update($data_pulang);

            if ($update) {
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

        }else{
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
}
