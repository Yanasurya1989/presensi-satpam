<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use App\Models\Kehadiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KehadiranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('layout.Presensi.sc');
    }

    public function out_sc()
    {
        return view('layout.Presensi.out-sc');
    }

    // public function rekap_presensi()
    // {
    //     return view('layout.Presensi.rekap');
    // }

    public function halamanrekap()
    {
        return view('layout.Presensi.tanpafilter');
    }

    public function tampildatakeseluruhan($tglawal, $tglakhir)
    {
        $presensi = Kehadiran::with('user')->whereBetween('tanggal', [$tglawal, $tglakhir])->orderBy('tanggal', 'asc')->get();
        return view('layout.Presensi.rekap', compact('presensi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $timezone = 'Asia/Jakarta';
        $date = new DateTime('now', new DateTimeZone($timezone));
        $tanggal = $date->format('Y-m-d');
        $localtime = $date->format('H:i:s');

        $kehadiran = Kehadiran::where([
            ['id_user', '=', $user->id],
            ['tanggal', '=', $tanggal],
        ])->first();

        if ($kehadiran) {
            dd("geus aya");
        } else {
            Kehadiran::created([
                'id_user' => $user->id,
                'tanggal' => $tanggal,
                'jam_masuk' => $localtime,
            ]);
        }
        return redirect('/presensi-sc');
    }

    public function outsc()
    {
        $user = Auth::user();
        $timezone = 'Asia/Jakarta';
        $date = new DateTime('now', new DateTimeZone($timezone));
        $tanggal = $date->format('Y-m-d');
        $localtime = $date->format('H:i:s');

        $presensi = Kehadiran::where([
            ['id_user', '=', $user->id],
            ['tanggal', '=', $tanggal],
        ])->first();

        $dt = [
            'jam_keluar' => $localtime,
            'jam_kerja' => date('H:i:s', strtotime($localtime) - strtotime($presensi->jammasuk))
        ];

        if ($presensi->jam_keluar == "") {
            $presensi->update($dt);
            return redirect('presensi-keluar');
        } else {
            dd("sudah ada");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Kehadiran $kehadiran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kehadiran $kehadiran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kehadiran $kehadiran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kehadiran $kehadiran)
    {
        //
    }
}
