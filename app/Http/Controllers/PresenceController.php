<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Inval;
use App\Models\Shift;
use App\Models\Overtime;
use App\Models\Presence;
use App\Models\UserShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\RekapPresensiExport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

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

    public function indexAdmin()
    {
        // Ambil semua data presensi
        $presences = Presence::with('user')->orderBy('tanggal', 'desc')->get();

        return view('admin.presensi.index', compact('presences'));
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

    // nambahin field lembur
    public function rekapDigantiFilter(Request $request)
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

    public function rekap(Request $request)
    {
        $query = Presence::query();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        $presences = $query->get();

        $totalHariPerUser = $presences->groupBy('user_id')->map->count();

        // Hitung lembur berdasarkan tanggal
        $lembur = Overtime::whereBetween('tgl_presensi', [$request->start_date, $request->end_date])
            ->get()
            ->groupBy('tgl_presensi');

        return view('layout.Presensi.rekap_security', compact('presences', 'totalHariPerUser', 'lembur'));
    }


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

        // Ambil shift user hari ini
        $userShiftToday = UserShift::where('user_id', $user->id)
            ->where('shift_date', $today)
            ->with('shift')
            ->first();

        $hasShiftToday = $userShiftToday !== null;

        // Default false
        $isShiftNow = false;

        if ($userShiftToday && $userShiftToday->shift) {
            $now = Carbon::now();
            $startTime = Carbon::parse($userShiftToday->shift->start_time);
            $endTime = Carbon::parse($userShiftToday->shift->end_time);

            // Penanganan shift yang melewati tengah malam
            if ($endTime->lessThan($startTime)) {
                $endTime->addDay();
            }

            $isShiftNow = $now->between($startTime, $endTime);
        }

        // Cek apakah user sudah presensi hari ini
        $presensiHariIni = Presence::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        return view('layout.Presensi.absen', compact(
            'hasShiftToday',
            'isShiftNow',
            'presensiHariIni',
            'userShiftToday'
        ));
    }

    public function rekapHarian(Request $request)
    {
        $user = auth()->user();

        // Ambil tanggal dari request atau default ke hari ini
        $tanggal = $request->input('tanggal', now()->toDateString());

        // Ambil presensi user di tanggal tersebut
        $presences = Presence::where('user_id', $user->id)
            ->whereDate('tanggal', $tanggal)
            ->get();

        // Ambil lembur user di tanggal tersebut
        $lembur = Overtime::where('user_id', $user->id)
            ->whereDate('start_time', $tanggal)
            ->get();

        return view('layout.Presensi.rekap_harian', compact('presences', 'lembur', 'tanggal'));
    }

    public function rekap_admin_all()
    {
        $presensi = DB::table('presence')
            ->join('users', 'users.id', '=', 'presence.user_id')
            ->select(
                'presence.tanggal as tanggal',
                'users.name as nama',
                DB::raw("'Presensi' as jenis"),
                'presence.jam as jam_in',
                'presence.jam_2 as jam_out',
                DB::raw("NULL as shift"),
                DB::raw("NULL as pengganti")
            );

        // Gabungkan semua shift lembur jadi satu
        $lembur = DB::table('overtimes')
            ->join('users', 'users.id', '=', 'overtimes.user_id')
            ->select(
                'overtimes.created_at as tanggal',
                'users.name as nama',
                DB::raw("'Lembur Shift 1' as jenis"),
                'overtimes.start_time_shift1 as jam_in',
                'overtimes.end_time_shift1 as jam_out',
                DB::raw("'Shift 1' as shift"),
                DB::raw("NULL as pengganti")
            )
            ->whereNotNull('start_time_shift1')
            ->unionAll(
                DB::table('overtimes')
                    ->join('users', 'users.id', '=', 'overtimes.user_id')
                    ->select(
                        'overtimes.created_at as tanggal',
                        'users.name as nama',
                        DB::raw("'Lembur Shift 2' as jenis"),
                        'overtimes.start_time_shift2 as jam_in',
                        'overtimes.end_time_shift2 as jam_out',
                        DB::raw("'Shift 2' as shift"),
                        DB::raw("NULL as pengganti")
                    )
                    ->whereNotNull('start_time_shift2')
            )
            ->unionAll(
                DB::table('overtimes')
                    ->join('users', 'users.id', '=', 'overtimes.user_id')
                    ->select(
                        'overtimes.created_at as tanggal',
                        'users.name as nama',
                        DB::raw("'Lembur Shift 3' as jenis"),
                        'overtimes.start_time_shift3 as jam_in',
                        'overtimes.end_time_shift3 as jam_out',
                        DB::raw("'Shift 3' as shift"),
                        DB::raw("NULL as pengganti")
                    )
                    ->whereNotNull('start_time_shift3')
            );

        $inval = DB::table('invals')
            ->join('users', 'users.id', '=', 'invals.user_id')
            ->select(
                'invals.created_at as tanggal',
                'users.name as nama',
                DB::raw("'Inval' as jenis"),
                'invals.time_start as jam_in',
                'invals.time_end as jam_out',
                DB::raw("NULL as shift"),
                'invals.pengganti'
            );

        $rekap = $presensi->unionAll($lembur)->unionAll($inval)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('layout.Presensi.admin.rekap.index', compact('rekap'));
    }

    public function pipot()
    {
        $presensi = Presence::with('user')->orderBy('tanggal')->get();
        $overtimes = Overtime::all();
        $invals = Inval::all();

        $dataGabungan = $presensi->map(function ($p) use ($overtimes, $invals) {
            $uid = $p->user_id;
            $tgl = $p->tanggal;

            // cari lembur berdasarkan user dan tanggal dari created_at
            $lembur = $overtimes->first(function ($item) use ($uid, $tgl) {
                return $item->user_id == $uid && Carbon::parse($item->created_at)->format('Y-m-d') == $tgl;
            });

            // cari inval berdasarkan user dan tanggal dari created_at
            $inval = $invals->first(function ($item) use ($uid, $tgl) {
                return $item->user_id == $uid && Carbon::parse($item->created_at)->format('Y-m-d') == $tgl;
            });

            return [
                'tanggal' => $tgl,
                'nama' => $p->user->name,
                'presensi_masuk' => $p->jam,
                'presensi_pulang' => $p->jam_2 ?? null,

                'lembur_shift1' => ($lembur && $lembur->start_time_shift1 && $lembur->end_time_shift1)
                    ? Carbon::parse($lembur->start_time_shift1)->format('H:i') . ' - ' . Carbon::parse($lembur->end_time_shift1)->format('H:i')
                    : null,

                'lembur_shift2' => ($lembur && $lembur->start_time_shift2 && $lembur->end_time_shift2)
                    ? Carbon::parse($lembur->start_time_shift2)->format('H:i') . ' - ' . Carbon::parse($lembur->end_time_shift2)->format('H:i')
                    : null,

                'lembur_shift3' => ($lembur && $lembur->start_time_shift3 && $lembur->end_time_shift3)
                    ? Carbon::parse($lembur->start_time_shift3)->format('H:i') . ' - ' . Carbon::parse($lembur->end_time_shift3)->format('H:i')
                    : null,

                'inval' => ($inval && $inval->time_start && $inval->time_end)
                    ? Carbon::parse($inval->time_start)->format('H:i') . ' - ' . Carbon::parse($inval->time_end)->format('H:i') . ' (Gantikan: ' . $inval->pengganti . ')'
                    : null,
            ];
        });

        return view('layout.Presensi.admin.rekap_pipot.pivot', compact('dataGabungan'));
    }

    public function edit($id)
    {
        $presence = Presence::findOrFail($id);
        return view('admin.presensi.edit', compact('presence'));
    }

    public function destroy($id)
    {
        $presence = Presence::findOrFail($id);
        $presence->delete();

        return redirect()->route('admin.presensi.index')->with('success', 'Data berhasil dihapus');
    }
}
