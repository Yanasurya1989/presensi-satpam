<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PresensiController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();
        $tanggal = now()->format('Y-m-d');
        $jam = now()->format('H:i:s');

        $presensi = Presensi::where('user_id', $user->id)->where('tanggal', $tanggal)->first();

        if ($presensi) {
            // Presensi pulang
            $presensi->update([
                'jam_pulang' => $jam,
                'lokasi_pulang' => $request->lokasi ?? $request->latitude . ',' . $request->longitude,
                'foto_pulang' => $request->photo
            ]);
        } else {
            // Presensi masuk
            Presensi::create([
                'user_id' => $user->id,
                'tanggal' => $tanggal,
                'jam_masuk' => $jam,
                'lokasi_masuk' => $request->lokasi ?? $request->latitude . ',' . $request->longitude,
                'foto_masuk' => $request->photo
            ]);
        }

        return redirect()->back()->with('success', 'Presensi berhasil!');
    }
}
