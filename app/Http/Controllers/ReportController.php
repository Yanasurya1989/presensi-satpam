<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $report = Report::all();
        return view('layout.admin.insert', compact('report'));
    }

    public function admin()
    {
        $report = Report::all();
        return view('layout.admin.rekap_admin', compact('report'));
    }

    public function view()
    {
        $role = Auth::user()->role->name;
        // dd($role);
        if ($role == 'User') {
            // dd('roleuser');
            $report = Report::where('id_user', Auth::user()->id)->get();
        } else {
            $report = Report::get();
        }
        return view('layout.admin.rekap', compact('report'));
    }

    public function view_admin()
    {
        $report = Report::where('id_user', Auth::user()->id)->get();
        // return view('layout.admin.all-daily', compact('report'));
        return view('layout.admin.all-daily', compact('report'));
    }

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
        // input
        $tanggal = Carbon::now()->format('Y-m-d');
        $id_user = $request->id_user;
        $shalat_wajib = $request->shalat_wajib;
        $qiyamul_lail = $request->qiyamul_lail;
        $tilawah = $request->tilawah;
        $duha = $request->duha;



        // proses
        $simpan = Report::create([
            'tanggal' => $tanggal,
            'id_user' => $id_user,
            'shalat_wajib' => $shalat_wajib,
            'qiyamul_lail' => $qiyamul_lail,
            'tilawah' => $tilawah,
            'duha' => $duha,
        ]);

        // output
        return redirect('/report');
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
    }
}
