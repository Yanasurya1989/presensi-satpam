<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rekap;
use App\Exports\RekapExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class Rekapcontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function exportRekap(Request $request)
    {
        return Excel::download(new RekapExport($request->month, $request->year), 'rekap.xlsx');
    }

    public function index(Request $request)
    {
        $users = User::withSum('report', 'shalat_wajib')
            ->withSum('report', 'qiyamul_lail')
            ->withSum('report', 'tilawah')
            ->withSum('report', 'duha')
            ->get();

        // dd($users->toArray()); // Lihat hasil apakah ada nilai total

        // return view('layout.admin.rekap_admin', compact('users'));
        return view('layout.admin.all-daily', compact('users'));

        // $month = $request->input('month', date('m'));
        // $year = $request->input('year', date('Y'));

        // $users = User::withSum(['reports as report_sum_shalat_wajib' => function ($query) use ($month, $year) {
        //     $query->whereMonth('created_at', $month)->whereYear('created_at', $year);
        // }], 'shalat_wajib')
        //     ->withSum(['reports as report_sum_qiyamul_lail' => function ($query) use ($month, $year) {
        //         $query->whereMonth('created_at', $month)->whereYear('created_at', $year);
        //     }], 'qiyamul_lail')
        //     ->withSum(['reports as report_sum_tilawah' => function ($query) use ($month, $year) {
        //         $query->whereMonth('created_at', $month)->whereYear('created_at', $year);
        //     }], 'tilawah')
        //     ->withSum(['reports as report_sum_duha' => function ($query) use ($month, $year) {
        //         $query->whereMonth('created_at', $month)->whereYear('created_at', $year);
        //     }], 'duha')
        //     ->get();

        // return view('layout.admin.rekap_admin', compact('users'));
    }

    // 

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Rekap $rekap)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rekap $rekap)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rekap $rekap)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rekap $rekap)
    {
        //
    }
}
