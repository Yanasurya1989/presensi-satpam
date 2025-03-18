<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Shift;
use Illuminate\Support\Facades\DB;

class ShiftAssignmentController extends Controller
{
    // public function index()
    // {
    //     $users = User::with('shifts')->get();
    //     $shifts = Shift::all();
    //     return view('shifts.assign', compact('users', 'shifts'));
    // }

    public function index()
    {
        // Ambil hanya user dengan role "security"
        $users = User::with('shifts')->whereHas('role', function ($query) {
            $query->where('name', 'scurity');
        })->get();

        // Ambil semua shift
        $shifts = Shift::all();

        return view('shifts.assign', compact('users', 'shifts'));
    }


    public function assign(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'shift_id' => 'required|exists:shifts,id',
            'shift_date' => 'required|date',
        ]);

        // Menambahkan shift ke user
        $user = User::findOrFail($request->user_id);
        $user->shifts()->attach($request->shift_id, ['shift_date' => $request->shift_date]);

        return redirect()->back()->with('success', 'Shift berhasil ditetapkan!');
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
}
