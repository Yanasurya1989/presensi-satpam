<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Mail\AttendanceNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

// Mail::to(Auth::user()->email)->send(new AttendanceNotification($attendance));

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function report(Request $request)
    {
        $attendances = Attendance::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($attendances);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
            'photo' => 'required|image|max:2048',
        ]);

        // Ambil koordinat kantor (misalnya)
        $officeLatitude = -6.200000;
        $officeLongitude = 106.816666;
        $radius = 100; // 100 meter

        if ($this->isWithinRadius($request->latitude, $request->longitude, $officeLatitude, $officeLongitude, $radius)) {
            // Simpan foto
            $photoPath = $request->file('photo')->store('photos', 'public');

            // Simpan ke database
            Attendance::create([
                'user_id' => Auth::id(),
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'photo' => $photoPath,
            ]);

            return response()->json(['message' => 'Presensi berhasil'], 200);
        }
        return response()->json(['message' => 'Anda berada di luar jangkauan!'], 403);
    }

    private function isWithinRadius($lat1, $lon1, $lat2, $lon2, $radius)
    {
        $earthRadius = 6371000; // dalam meter
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return $distance <= $radius;
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
