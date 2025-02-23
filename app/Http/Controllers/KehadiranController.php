<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use App\Models\User;
use App\Models\Kehadiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KehadiranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hari_ini = date("Y-m-d");
        $id = Auth::user()->id;
        $cek = DB::table('selfi_presensi')->where('tgl_presensi', $hari_ini)->where('id', $id)->count();

        // return view('layout.Presensi.sc');
        return view('layout.Presensi.presensi_masuk', compact('cek'));
    }

    public function out_sc()
    {
        // $today = date("Y-m-d");
        // $id = Auth::guard('users')->user()->id;
        // $cek = DB::table('presensi')->where('tanggal_presensi', $today)->where('id', $id)->count();
        // return view('layout.Presensi.out-sc', compact('cek'));
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

    public function viewjam()
    {
        $jam = User::all();
        return view('layout.Presensi.tes', compact('jam'));
    }

    public function create()
    {
        Kehadiran::create([
            'jam_masuk' => now()
        ]);

        return redirect()->back()->with('success', 'atos mang');
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

    public function sendpresensi(Request $request)
    {
        $id = Auth::guard('users')->user()->id;
        $tanggal_presensi = date("Y-m-d");
        $jam = date("H:i:s");
        $lokasi = $request->lokasi;
        $image = $request->image;
        $folderPath = "public/uploads/absensi/";
        $formatName = $id . "-" . $tanggal_presensi;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $filename = $formatName . ".png";
        $file = $folderPath . $filename;

        $cek = DB::table('presensi')->where('tanggal_presensi', $tanggal_presensi)->where('id', $id)->count();
        if ($cek > 0) {
            $data_pulang = [

                'jam_out' => $jam,
                'foto_out' => $filename,
                'lokasi_out' => $lokasi
            ];
            $update = DB::table('presensi')->where('tanggal_presensi', $tanggal_presensi)->where('id', $id)->count();
            if ($update) {
                echo "success|titidije|out";
                Storage::put($file, $image_base64);
            } else {
                echo "error|Maaf gagal absen|out";
            }
        } else {
            $data = [
                'id' => $id,
                'tanggal_presensi' => $tanggal_presensi,
                'jam_in' => $jam,
                'foto_in' => $filename,
                'lokasi_in' => $lokasi
            ];
            $simpan = DB::table('presensi')->insert($data);
            if ($simpan) {
                echo 'success|selamat bekerja|in';
                Storage::put($file, $image_base64);
            } else {
                echo "error|Maaf gagal absen|in";
            }
        }
    }

    public function kirim_hadir(Request $request)
    {
        $id = Auth::user()->id;
        // dd($id);
        $tgl_presensi = date("Y-m-d");
        $jam = date("H:i:s");
        $latitudesekolah = -6.971690;
        $longitudesekolah = 107.752041;
        $lokasi = $request->lokasi;
        $lokasi_user = explode(',', $lokasi);
        // dd($lokasi_user);
        $latitude_user = $lokasi_user[0];
        $longitude_user = $lokasi_user[1];
        $jarak = $this->distance($latitudesekolah, $longitudesekolah, $latitude_user, $longitude_user);
        $radius = round($jarak['meters']);

        // $image = $request->image;
        // dd($image);
        // $folderPath = "public/uploads/absensi/";
        // $formatName = $id . "-" . $tgl_presensi;
        // $image_parts = explode(",", $image);
        // $image_base64 = base64_decode($image_parts[1]);
        // $filename = $formatName . ".png";
        // $file = $folderPath . $filename;
        $data = [
            'id' => $id,
            'tgl_presensi' => $tgl_presensi,
            'jam_in' => $jam,
            // 'foto_in' => $filename,
            // 'lokasi_in' => $lokasi
        ];

        $cek = DB::table('selfi_presensi')->where('tgl_presensi', $tgl_presensi)->where('id', $id)->count();
        // dd($cek);
        if ($radius > 20) {
            echo "error|Anda berada diluar radius, jarak bapak/ibu " . $radius . " meter dari titik koordinat|radius";
        } else {
            if (is_null($cek)) {
                $data_pulang = [
                    'jam_out' => $jam,
                    // 'foto_out' => $filename,
                    'lokasi_out' => $lokasi
                ];
                $update = DB::table('selfi_presensi')->where('tgl_presensi', $tgl_presensi)->where('id', $id)->update($data_pulang);
                if ($update) {
                    echo "success|titidije|out";
                    // Storage::put($file, $image_base64);
                } else {
                    echo "error|Maaf gagal absen|out";
                }
            } else {
                $data = [
                    'id' => $id,
                    'tgl_presensi' => $tgl_presensi,
                    'jam_in' => $jam,
                    // 'foto_in' => $filename,
                    // 'lokasi_in' => $lokasi
                ];
                $simpan = DB::table('selfi_presensi')->insert($data);
                if ($simpan) {
                    // echo 'success|selamat bekerja |in';
                    return redirect()->route('presensi_sc');
                    // Storage::put($file, $image_base64);
                } else {
                    echo "error|Maaf gagal absen|in";
                }
            }
        }
    }

    //Menghitung Jarak
    function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers / 1000;
        return compact('meters');
    }
}
