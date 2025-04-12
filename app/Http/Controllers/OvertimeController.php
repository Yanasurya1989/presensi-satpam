<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Overtime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OvertimeController extends Controller
{
    public function index()
    {
        $overtimes = Overtime::where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('layout.Presensi.lembur.index', compact('overtimes'));
    }

    public function create()
    {
        $users = User::all(); // untuk dropdown user
        // return view('overtimes.create', compact('users'));
        return view('layout.Presensi.lembur.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);
        $totalMinutes = $start->diffInMinutes($end);

        Overtime::create([
            'user_id' => auth()->user()->id,
            'start_time' => $start,
            'end_time' => $end,
            'total_minutes' => $totalMinutes,
        ]);

        return redirect()->route('lembur.index')->with('success', 'Lembur berhasil ditambahkan!');
    }
}
