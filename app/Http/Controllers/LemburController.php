<?php

namespace App\Http\Controllers;

use App\Models\Overtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LemburController extends Controller
{
    public function index()
    {
        $data = Overtime::where('user_id', Auth::id())->latest()->get();
        return view('lembur.index', compact('data'));
    }

    public function create()
    {
        return view('lembur.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl_lembur' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'nullable',
            'keterangan' => 'nullable|string|max:255',
            'poto_mulai' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'poto_selesai' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = new Overtime();
        $data->user_id = auth()->id();
        $data->tgl_lembur = $request->tgl_lembur;
        $data->jam_mulai = $request->jam_mulai;
        $data->jam_selesai = $request->jam_selesai;
        $data->keterangan = $request->keterangan;

        if ($request->hasFile('poto_mulai')) {
            $data->poto_mulai = $request->file('poto_mulai')->store('lembur', 'public');
        }

        if ($request->hasFile('poto_selesai')) {
            $data->poto_selesai = $request->file('poto_selesai')->store('lembur', 'public');
        }

        $data->save();

        return redirect()->route('lembur.index')->with('success', 'Data lembur berhasil disimpan!');
    }
}
