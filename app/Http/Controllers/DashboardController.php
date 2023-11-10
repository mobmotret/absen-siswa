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
        $bulanini = date("m") * 1;
        $tahunini = date("Y");
        $nis = Auth::guard('siswa')->user()->nis;
        $presensihariini = DB::table('presensi')->where('nis', $nis)->where('tgl_presensi', $hariini)->first();
        $historibulanini = DB::table('presensi')->whereRaw('MONTH(tgl_presensi)="'.$bulanini. '"')
                ->whereRaw('YEAR(tgl_presensi)="'.$tahunini. '"')->where('nis', $nis)
                ->orderBy('tgl_presensi')
                ->get();

        $rekappresensi = DB::table('presensi')
            ->selectRaw('COUNT(nis) as jmlhadir, SUM(IF(jam_in > "07:00",1,0)) as jmlterlambat')
            ->where('nis', $nis)
            ->whereRaw('MONTH(tgl_presensi)="'.$bulanini. '"')
            ->whereRaw('YEAR(tgl_presensi)="'.$tahunini. '"')
            ->first();

        $leaderboard = DB::table('presensi')
            ->join('siswa', 'presensi.nis', '=' , 'siswa.nis')
            ->where('tgl_presensi', $hariini)
            ->orderBy('jam_in')
            ->get();

        $namabulan = ['','Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        return view('dashboard.dashboard', compact('presensihariini', 'historibulanini', 'namabulan','bulanini','tahunini','rekappresensi','leaderboard'));
    }
}
