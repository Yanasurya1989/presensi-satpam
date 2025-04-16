<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shift;
use App\Models\UserShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

    public function remove($userId, $shiftId, $shiftDate)
    {
        DB::table('user_shifts')
            ->where('user_id', $userId)
            ->where('shift_id', $shiftId)
            ->where('shift_date', $shiftDate)
            ->delete();

        return redirect()->back()->with('success', 'Shift berhasil dihapus!');
    }

    public function destroy($id)
    {
        $userShift = UserShift::findOrFail($id);
        $userShift->delete();

        return redirect()->back()->with('success', 'Shift berhasil dihapus.');
    }

    public function presensiForm()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        // Cek apakah user sudah dijadwalkan shift hari ini
        $hasShiftToday = UserShift::where('user_id', $user->id)
            ->where('shift_date', $today)
            ->exists();

        return view('layout.Presensi.absen', compact('hasShiftToday'));
    }

    public function edit($id)
    {
        $userShift = UserShift::findOrFail($id);
        $users = User::all();
        $shifts = Shift::all();
        return view('shifts.edit', compact('userShift', 'users', 'shifts'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'shift_id' => 'required|exists:shifts,id',
            'shift_date' => 'required|date',
        ]);

        $userShift = UserShift::findOrFail($id);
        $userShift->update([
            'user_id' => $request->user_id,
            'shift_id' => $request->shift_id,
            'shift_date' => $request->shift_date,
        ]);

        return redirect()->route('shift.assign')->with('success', 'Shift berhasil diperbarui.');
    }
}
