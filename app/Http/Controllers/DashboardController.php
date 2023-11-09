<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $hariini = date("Y-m-d");
        $nis = Auth::guard('siswa')->user()->nis;
        $presensihariini = DB::table('presensi')->where('nis', $nis)->where('tgl_presensi', $hariini)->first();
        return view('dashboard.dashboard', compact('presensihariini'));
    }
}
