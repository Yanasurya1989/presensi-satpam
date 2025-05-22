<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\UserShift;
use App\Models\User;
use App\Models\Overtime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Presence;

class OvertimeController extends Controller
{
    public function indexbentarMauNyoba()
    {
        $overtimes = Overtime::where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('layout.Presensi.lembur.index', compact('overtimes'));
    }

    public function index()
    {
        $overtimes = Overtime::with('user')->get();

        $groupedOvertime = [];

        foreach ($overtimes as $overtime) {
            $userName = $overtime->user->name;

            if (!isset($groupedOvertime[$userName])) {
                $groupedOvertime[$userName] = [];
            }

            // Shift 1
            if ($overtime->start_time_shift1 && $overtime->end_time_shift1) {
                $groupedOvertime[$userName][1] = [
                    'start_time' => $overtime->start_time_shift1,
                    'end_time' => $overtime->end_time_shift1,
                    'total_minutes' => $overtime->total_minutes_shift1,
                ];
            }

            // Shift 2
            if ($overtime->start_time_shift2 && $overtime->end_time_shift2) {
                $groupedOvertime[$userName][2] = [
                    'start_time' => $overtime->start_time_shift2,
                    'end_time' => $overtime->end_time_shift2,
                    'total_minutes' => $overtime->total_minutes_shift2,
                ];
            }

            // Shift 3
            if ($overtime->start_time_shift3 && $overtime->end_time_shift3) {
                $groupedOvertime[$userName][3] = [
                    'start_time' => $overtime->start_time_shift3,
                    'end_time' => $overtime->end_time_shift3,
                    'total_minutes' => $overtime->total_minutes_shift3,
                ];
            }
        }

        return view('layout.Presensi.lembur.index', compact('groupedOvertime'));
    }

    public function create($shift)
    {
        $user = auth()->user();
        $today = Carbon::today();

        $jadwalShift = UserShift::with('shift')
            ->where('user_id', $user->id)
            ->whereDate('shift_date', $today)
            ->first();

        return view('layout.Presensi.lembur.create', [
            'shift' => $shift,
            'jadwalShift' => $jadwalShift,
        ]);
    }

    // update form lembur sesuai shift
    public function store(Request $request)
    {
        $request->validate([
            'shift' => 'required|in:1,2,3',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);
        $totalMinutes = $start->diffInMinutes($end);

        // Siapkan data lembur umum
        $data = [
            'user_id' => auth()->id(),
            'start_time' => $start,
            'end_time' => $end,
            'total_minutes' => $totalMinutes,
        ];

        // Tambahkan data sesuai shift
        switch ($request->shift) {
            case 1:
                $data['start_time_shift1'] = $start;
                $data['end_time_shift1'] = $end;
                $data['total_minutes_shift1'] = $totalMinutes;
                break;
            case 2:
                $data['start_time_shift2'] = $start;
                $data['end_time_shift2'] = $end;
                $data['total_minutes_shift2'] = $totalMinutes;
                break;
            case 3:
                $data['start_time_shift3'] = $start;
                $data['end_time_shift3'] = $end;
                $data['total_minutes_shift3'] = $totalMinutes;
                break;
        }

        // Simpan ke DB
        Overtime::create($data);

        return redirect()->route('lembur.index')->with('success', 'Data lembur shift ' . $request->shift . ' berhasil disimpan.');
    }

    public function storeIniUtkRekapAdmin(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'presensi_id' => 'nullable|exists:selfie_presensi,id',
            'jam_in' => 'required|date',
            'jam_out' => 'required|date|after:jam_in',
            'shift' => 'required|in:1,2,3',
        ]);

        $presensi = Presence::find($request->presensi_id);
        $tgl_presensi = $presensi->tgl_presensi ?? \Carbon\Carbon::now()->toDateString();

        $start = \Carbon\Carbon::parse($request->jam_in);
        $end = \Carbon\Carbon::parse($request->jam_out);
        $total_minutes = $end->diffInMinutes($start);

        $data = [
            'user_id' => $request->user_id,
            'presensi_id' => $request->presensi_id ?? null,
            'tgl_presensi' => $tgl_presensi,
            'jam_in' => $request->jam_in,
            'jam_out' => $request->jam_out,
            'poto_in' => $request->poto_in ?? null,
            'poto_out' => $request->poto_out ?? null,
        ];

        if ($request->shift == 1) {
            $data['start_time_shift1'] = $request->jam_in;
            $data['end_time_shift1'] = $request->jam_out;
            $data['total_minutes_shift1'] = $total_minutes;
        } elseif ($request->shift == 2) {
            $data['start_time_shift2'] = $request->jam_in;
            $data['end_time_shift2'] = $request->jam_out;
            $data['total_minutes_shift2'] = $total_minutes;
        } elseif ($request->shift == 3) {
            $data['start_time_shift3'] = $request->jam_in;
            $data['end_time_shift3'] = $request->jam_out;
            $data['total_minutes_shift3'] = $total_minutes;
        }

        \App\Models\Overtime::create($data);

        return redirect()->route('lembur.index')->with('success', 'Data lembur berhasil ditambahkan.');
    }
}
