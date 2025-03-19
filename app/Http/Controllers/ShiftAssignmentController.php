<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shift;
use App\Models\UserShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShiftAssignmentController extends Controller
{
    public function assign()
    {
        $users = User::all();  // Mengambil semua user
        $shifts = Shift::all(); // Mengambil semua shift
        $userShifts = UserShift::with(['user', 'shift'])->get(); // Mengambil shift dengan relasi user & shift

        return view('shifts.assign', compact('users', 'shifts', 'userShifts'));
    }

    public function store(Request $request)
    {
        // Debugging: Cek apakah request diterima dengan benar
        // dd($request->all());

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'shift_id' => 'required|exists:shifts,id',
            'shift_date' => 'required|date',
        ]);

        // Simpan ke database
        $userShift = UserShift::create([
            'user_id' => $request->user_id,
            'shift_id' => $request->shift_id,
            'shift_date' => $request->shift_date,
        ]);

        if ($userShift) {
            return redirect()->back()->with('success', 'Shift berhasil ditetapkan.');
        }

        return redirect()->back()->with('error', 'Gagal menetapkan shift.');
    }
    // public function index()
    // {
    //     $users = User::with('shifts')->get();
    //     $shifts = Shift::all();
    //     return view('shifts.assign', compact('users', 'shifts'));
    // }

    // public function index()
    // {
    //     $users = User::with('shifts')->whereHas('role', function ($query) {
    //         $query->where('name', 'scurity');
    //     })->get();

    //     $shifts = Shift::all();

    //     return view('shifts.assign', compact('users', 'shifts'));
    // }

    // public function index()
    // {
    //     $users = User::with('shifts')->get();
    //     $shifts = Shift::all();

    //     // Ambil daftar shift yang sudah ditetapkan
    //     $userShifts = DB::table('user_shifts')
    //         ->join('users', 'user_shifts.user_id', '=', 'users.id')
    //         ->join('shifts', 'user_shifts.shift_id', '=', 'shifts.id')
    //         ->select(
    //             'user_shifts.id',
    //             'users.name as user_name',
    //             'shifts.name as shift_name',
    //             'shifts.start_time',
    //             'shifts.end_time',
    //             'user_shifts.shift_date'
    //         )
    //         ->get();

    //     return view('shifts.assign', compact('users', 'shifts', 'userShifts'));
    // }

    public function index()
    {
        // Ambil semua user dengan shift-nya
        $users = User::with('shifts')->get();

        // Ambil semua shift
        $shifts = Shift::all();

        // Ambil user_shifts dengan relasi user dan shift
        $userShifts = UserShift::with(['user', 'shift'])->get();

        return view('shifts.assign', compact('users', 'shifts', 'userShifts'));
    }

    // public function assign(Request $request)
    // {
    //     $request->validate([
    //         'user_id' => 'required|exists:users,id',
    //         'shift_id' => 'required|exists:shifts,id',
    //         'shift_date' => 'required|date',
    //     ]);


    //     $user = User::findOrFail($request->user_id);
    //     $user->shifts()->attach($request->shift_id, ['shift_date' => $request->shift_date]);

    //     return redirect()->back()->with('success', 'Shift berhasil ditetapkan!');
    // }

    // public function assign(Request $request)
    // {
    //     $request->validate([
    //         'user_id' => 'required|exists:users,id',
    //         'shift_id' => 'required|exists:shifts,id',
    //         'shift_date' => 'required|date',
    //     ]);

    //     // Menambahkan shift ke user
    //     $user = User::findOrFail($request->user_id);
    //     $user->shifts()->attach($request->shift_id, ['shift_date' => $request->shift_date]);

    //     return redirect()->back()->with('success', 'Shift berhasil ditetapkan!');
    // }

    // public function assign(Request $request)
    // {
    //     $request->validate([
    //         'user_id' => 'required|exists:users,id',
    //         'shift_id' => 'required|exists:shifts,id',
    //         'week_start' => 'required|date',
    //         'week_end' => 'required|date',
    //     ]);

    //     $user = User::findOrFail($request->user_id);

    //     // Loop dari week_start sampai week_end untuk memasukkan shift setiap hari
    //     $start = new \Carbon\Carbon($request->week_start);
    //     $end = new \Carbon\Carbon($request->week_end);

    //     while ($start <= $end) {
    //         DB::table('user_shifts')->insert([
    //             'user_id' => $request->user_id,
    //             'shift_id' => $request->shift_id,
    //             'week_start' => $request->week_start,
    //             'week_end' => $request->week_end,
    //             'shift_date' => $start->toDateString(),
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ]);

    //         $start->addDay(); // Pindah ke hari berikutnya
    //     }

    //     return redirect()->back()->with('success', 'Shift berhasil ditetapkan untuk seluruh minggu!');
    // }

    // public function assign(Request $request)
    // {
    //     $request->validate([
    //         'user_id' => 'required|exists:users,id',
    //         'shift_id' => 'required|exists:shifts,id',
    //         'shift_date' => 'required|date',
    //     ]);

    //     $userId = $request->user_id;
    //     $shiftId = $request->shift_id;
    //     $shiftDate = $request->shift_date;

    //     $existingShift = DB::table('user_shifts')
    //         ->where('user_id', $userId)
    //         ->where('shift_date', $shiftDate)
    //         ->exists();

    //     if ($existingShift) {
    //         return redirect()->back()->with('error', 'User sudah memiliki shift pada tanggal ini!');
    //     }


    //     DB::table('user_shifts')->insert([
    //         'user_id' => $userId,
    //         'shift_id' => $shiftId,
    //         'shift_date' => $shiftDate,
    //         'created_at' => now(),
    //         'updated_at' => now()
    //     ]);

    //     return redirect()->back()->with('success', 'Shift berhasil ditetapkan!');
    // }

    public function remove($userId, $shiftId, $shiftDate)
    {
        DB::table('user_shifts')
            ->where('user_id', $userId)
            ->where('shift_id', $shiftId)
            ->where('shift_date', $shiftDate)
            ->delete();

        return redirect()->back()->with('success', 'Shift berhasil dihapus!');
    }


    // public function remove($userId, $shiftId, $shiftDate)
    // {
    //     DB::table('user_shifts')
    //         ->where('user_id', $userId)
    //         ->where('shift_id', $shiftId)
    //         ->where('shift_date', $shiftDate)
    //         ->delete();

    //     return redirect()->back()->with('success', 'Shift berhasil dihapus!');
    // }

    // public function remove($userId, $shiftId, $weekStart, $weekEnd)
    // {
    //     DB::table('user_shifts')
    //         ->where('user_id', $userId)
    //         ->where('shift_id', $shiftId)
    //         ->where('week_start', $weekStart)
    //         ->where('week_end', $weekEnd)
    //         ->delete();

    //     return redirect()->back()->with('success', 'Shift berhasil dihapus!');
    // }

    public function destroy($id)
    {
        $userShift = UserShift::findOrFail($id);
        $userShift->delete();

        return redirect()->back()->with('success', 'Shift berhasil dihapus.');
    }
}
