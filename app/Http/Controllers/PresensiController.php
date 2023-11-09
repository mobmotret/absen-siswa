<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $image_base64 = base64_decode($image_part(1));
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;
        Storage::put($file, $image_base64);
        echo "0";
    }

}
