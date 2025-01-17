<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AjaxController extends Controller
{
    public function submit(Request $request)
    {
        $user = Auth::user();
        //dd($user);

        //check status absen

        $absen = $this->check_absen($user->id);
        if ($absen == null) {
            //insert presensi
            $presensi = array(
                'user_id' => $user->id,
                'datang' => Carbon::now()
            );
            //insert absen
            Presensi::create($presensi);
            $message = 'absen masuk';
        } else {
            //update jam pulang
            Presensi::where('user_id', $user->id)->whereDate('created_at', Carbon::today())
                ->update(['pulang' => Carbon::now()]);
            $message = 'absen pulang';
        }

        return response()->json([
            'message' => $message,
        ]);
    }

    function check_absen($user)
    {
        //handling check absen user

        $absen = Presensi::where('user_id', $user)->whereDate('created_at', Carbon::today())->first();
        return $absen;
    }
}
