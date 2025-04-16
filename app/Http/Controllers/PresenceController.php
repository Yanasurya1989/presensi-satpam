<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Presence;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Overtime;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapPresensiExport;
use Illuminate\Support\Facades\Auth;
use App\Models\UserShift;

class PresenceController extends Controller
{
    public function export(Request $request)
    {
        // Validasi input bulan dan tahun
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:' . date('Y'),
        ]);

        // Ambil input bulan dan tahun
        $month = $request->input('month');
        $year = $request->input('year');

        // Buat nama file dinamis
        $filename = 'rekap_presensi_' . $month . '_' . $year . '.xlsx';

        // Lakukan proses export menggunakan RekapPresensiExport
        return Excel::download(new RekapPresensiExport($month, $year), $filename);
    }

    public function index()
    {
        $user = auth()->user();
        $now = now();

        $jumlahHariHadir = Presence::where('user_id', $user->id)
            ->whereMonth('tanggal', $now->month)
            ->whereYear('tanggal', $now->year)
            ->distinct('tanggal')
            ->count('tanggal');

        return view('layout.Presensi.absen', compact('jumlahHariHadir'));
    }

    // public function rekap(Request $request)
    // {
    //     $user = auth()->user();

    //     // Ambil bulan dan tahun dari request (default: bulan dan tahun sekarang)
    //     $month = $request->input('month', now()->month);
    //     $year = $request->input('year', now()->year);

    //     // Ambil semua presensi user untuk bulan dan tahun tersebut
    //     $presences = Presence::where('user_id', $user->id)
    //         ->whereMonth('tanggal', $month)
    //         ->whereYear('tanggal', $year)
    //         ->orderBy('tanggal', 'asc')
    //         ->get();

    //     // Hitung total hari hadir (tanggal unik)
    //     $totalHariPerUser = [
    //         $user->id => $presences->unique('tanggal')->count(),
    //     ];

    //     return view('layout.Presensi.rekap_security', compact('presences', 'month', 'year', 'totalHariPerUser'));
    // }

    // nambahin field lembur
    public function rekap(Request $request)
    {
        $user = auth()->user();

        // Ambil bulan dan tahun dari request (default: bulan dan tahun sekarang)
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        // Ambil semua presensi user untuk bulan dan tahun tersebut
        $presences = Presence::where('user_id', $user->id)
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->orderBy('tanggal', 'asc')
            ->get();

        // Ambil semua lembur user untuk bulan dan tahun tersebut
        $lembur = Overtime::where('user_id', $user->id)
            ->whereMonth('start_time', $month)
            ->whereYear('start_time', $year)
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->start_time)->format('Y-m-d');
            });


        // Hitung total hari hadir (tanggal unik)
        $totalHariPerUser = [
            $user->id => $presences->unique('tanggal')->count(),
        ];

        // Kirim data ke view
        return view('layout.Presensi.rekap_security', compact(
            'presences',
            'month',
            'year',
            'totalHariPerUser',
            'lembur' // <- kirim ke blade
        ));
    }

    // public function rekapAdmin(Request $request)
    // {
    //     $useFilter = false;

    //     if ($request->filled('start') && $request->filled('end')) {
    //         $useFilter = true;
    //         $startDate = Carbon::parse($request->input('start'));
    //         $endDate = Carbon::parse($request->input('end'));
    //     } else {
    //         $startDate = Carbon::now()->startOfMonth();
    //         $endDate = (clone $startDate)->addMonth()->subDay();
    //     }

    //     $users = User::all();

    //     $rekap = $users->map(function ($user) use ($useFilter, $startDate, $endDate) {
    //         $queryPresensi = $user->presences();
    //         if ($useFilter) {
    //             $queryPresensi->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()]);
    //         }
    //         $totalHadir = $queryPresensi->count();

    //         $latestPresence = $queryPresensi->latest('tanggal')->first();
    //         $foto = $latestPresence ? $latestPresence->photo : null;

    //         $queryLembur = Overtime::where('user_id', $user->id);
    //         if ($useFilter) {
    //             $queryLembur->whereBetween('start_time', [$startDate, $endDate]);
    //         }
    //         $totalMenitLembur = $queryLembur->sum('total_minutes');

    //         return [
    //             'user' => $user,
    //             'total_hadir' => $totalHadir,
    //             'total_menit_lembur' => $totalMenitLembur,
    //             'foto' => $foto,
    //         ];
    //     });

    //     return view('layout.Presensi.rekap_admin_security', compact('rekap', 'useFilter', 'startDate', 'endDate'));
    // }

    public function rekapAdmin(Request $request)
    {
        $useFilter = false;

        if ($request->filled('start') && $request->filled('end')) {
            $useFilter = true;
            $startDate = Carbon::parse($request->input('start'));
            $endDate = Carbon::parse($request->input('end'));
        } else {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = (clone $startDate)->addMonth()->subDay();
        }

        $users = User::all();

        $rekap = $users->map(function ($user) use ($useFilter, $startDate, $endDate) {
            $queryPresensi = $user->presences();

            if ($useFilter) {
                $queryPresensi->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()]);
            }

            // Untuk user dengan dua shift dalam sehari
            if ($user->id == 30) {
                // Hitung kehadiran berdasarkan jumlah tanggal unik
                $totalHadir = $queryPresensi->distinct('tanggal')->count('tanggal');
            } else {
                // User biasa, hitung total presensi (asumsinya 1 shift per hari)
                $totalHadir = $queryPresensi->count();
            }

            $latestPresence = $queryPresensi->latest('tanggal')->first();
            $foto = $latestPresence ? $latestPresence->photo : null;

            $queryLembur = Overtime::where('user_id', $user->id);
            if ($useFilter) {
                $queryLembur->whereBetween('start_time', [$startDate, $endDate]);
            }
            $totalMenitLembur = $queryLembur->sum('total_minutes');

            return [
                'user' => $user,
                'total_hadir' => $totalHadir,
                'total_menit_lembur' => $totalMenitLembur,
                'foto' => $foto,
            ];
        });

        return view('layout.Presensi.rekap_admin_security', compact('rekap', 'useFilter', 'startDate', 'endDate'));
    }


    public function store(Request $request)
    {
        $user = auth()->user();
        $now = now();

        // Validasi input
        $request->validate([
            'photo' => 'required', // tambahkan validasi untuk foto
            'tipe'  => 'required', // validasi tipe masuk/keluar (opsional, tapi baik ada)
        ]);

        // Cek apakah sudah presensi hari ini
        $sudahPresensi = Presence::where('user_id', $user->id)
            ->whereDate('tanggal', $now->toDateString())
            ->exists();

        if ($sudahPresensi) {
            return redirect()->back()->with('error', 'Kamu sudah presensi hari ini.');
        }

        // Simpan presensi
        Presence::create([
            'user_id' => $user->id,
            'tanggal' => $now->toDateString(),
            'jam'     => $now->toTimeString(),
            'photo'   => $request->photo, // simpan base64 image ke database
        ]);

        return redirect()->back()->with('success', 'Presensi berhasil disimpan!');
    }


    public function presensiForm()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        // Cek apakah user sudah dijadwalkan shift hari ini
        $hasShiftToday = UserShift::where('user_id', $user->id)
            ->where('shift_date', $today)
            ->exists();

        // Cek apakah user sudah presensi hari ini
        $presensiHariIni = Presence::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first(); // null kalau belum presensi

        return view('layout.Presensi.absen', compact('hasShiftToday', 'presensiHariIni'));
    }
}
