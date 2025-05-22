<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Inval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvalController extends Controller
{

    public function index()
    {
        $now = Carbon::now();

        // Ambil dari query string jika ada
        $start = request('start') ? Carbon::parse(request('start'))->startOfDay() : (
            $now->copy()->day < 25
            ? $now->copy()->subMonth()->day(25)->startOfDay()
            : $now->copy()->day(25)->startOfDay()
        );

        $end = request('end') ? Carbon::parse(request('end'))->endOfDay() : $start->copy()->addMonth()->day(24)->endOfDay();

        $invals = Inval::where('user_id', Auth::id())
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalInval = $invals->count();

        return view('layout.Presensi.inval.index', compact('invals', 'start', 'end', 'totalInval'));
    }

    public function create()
    {
        $users = User::whereHas('role', function ($q) {
            $q->where('name', 'scurity');
        })->get();
        return view('layout.Presensi.inval.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'time_start' => 'required|date_format:H:i',
            'time_end' => 'required|date_format:H:i|after:time_start',
            'pengganti' => 'required|exists:users,name',
        ]);

        Inval::create([
            'user_id' => Auth::id(),
            'time_start' => $request->time_start,
            'time_end' => $request->time_end,
            'pengganti' => $request->pengganti,
        ]);

        // dd('Berhasil masuk ke store');

        return redirect()->back()->with('success', 'Data Inval berhasil disimpan!');
    }

    public function storeIniUtkRekapDiadmin(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'presensi_id' => 'nullable|exists:selfie_presensi,id',
            'time_start' => 'required',
            'time_end' => 'required',
            'pengganti' => 'required|string',
        ]);

        $presensi = Presence::find($request->presensi_id);

        \App\Models\Inval::create([
            'user_id' => $request->user_id,
            'presensi_id' => $request->presensi_id ?? null,
            'time_start' => $request->time_start,
            'time_end' => $request->time_end,
            'pengganti' => $request->pengganti,
            'tgl_presensi' => $presensi->tgl_presensi ?? \Carbon\Carbon::now()->toDateString(),
        ]);

        return redirect()->route('inval.index')->with('success', 'Data inval berhasil ditambahkan.');
    }
}
