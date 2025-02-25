<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Report;
use Illuminate\Http\Request;
use App\Exports\DailyReportExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

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
        // $report = Report::all();
        $report = Report::where('id_user', Auth::user()->id)->get();
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

    public function report_all_user()
    {
        // $users = User::withSum('report', 'shalat_wajib')
        //     ->withSum('report', 'qiyamul_lail')
        //     ->withSum('report', 'tilawah')
        //     ->withSum('report', 'duha')
        //     ->get();

        // dd($users->toArray()); // Lihat hasil apakah ada nilai total

        // return view('layout.admin.rekap_admin', compact('users'));
        // return view('layout.admin.all-daily', compact('users'));

        $users = collect(DB::table('users')
            ->leftJoin('report', 'report.id_user', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                'users.divisi',
                DB::raw('SUM(report.shalat_wajib) as report_sum_shalat_wajib'),
                DB::raw('SUM(report.qiyamul_lail) as report_sum_qiyamul_lail'),
                DB::raw('SUM(report.tilawah) as report_sum_tilawah'),
                DB::raw('SUM(report.duha) as report_sum_duha')
            )
            ->groupBy('users.id', 'users.name', 'users.divisi')
            ->get());

        return view('layout.admin.all-daily', compact('users'));
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
    // public function store(Request $request)
    // {

    //     $tanggal = Carbon::now()->format('Y-m-d');
    //     $id_user = $request->id_user;
    //     $shalat_wajib = $request->shalat_wajib;
    //     $qiyamul_lail = $request->qiyamul_lail;
    //     $tilawah = $request->tilawah;
    //     $duha = $request->duha;


    //     $simpan = Report::create([
    //         'tanggal' => $tanggal,
    //         'id_user' => $id_user,
    //         'shalat_wajib' => $shalat_wajib,
    //         'qiyamul_lail' => $qiyamul_lail,
    //         'tilawah' => $tilawah,
    //         'duha' => $duha,
    //     ]);


    //     return redirect('/report');
    // }

    public function store(Request $request)
    {
        $tanggal = Carbon::now()->format('Y-m-d');
        $id_user = $request->id_user;

        // Cek apakah user sudah mengisi data hari ini
        $cek = Report::where('id_user', $id_user)->where('tanggal', $tanggal)->first();
        if ($cek) {
            return redirect('/report')->with('error', 'Anda sudah mengisi data hari ini.');
        }

        // Jika belum ada, simpan data
        Report::create([
            'tanggal' => $tanggal,
            'id_user' => $id_user,
            'shalat_wajib' => $request->shalat_wajib,
            'qiyamul_lail' => $request->qiyamul_lail,
            'tilawah' => $request->tilawah,
            'duha' => $request->duha,
        ]);

        return redirect('/report')->with('success', 'Data berhasil disimpan.');
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
        $id = decrypt($report);
        $deleteMarketer = User::where('id', $id)->delete();
        return redirect('/users')->with('success', 'Atos dihapus mang');
    }

    public function delete($id)
    {
        $report = Report::where('id_user', $id)->delete();

        if ($report) {
            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus data');
        }
    }

    public function export()
    {
        $data = DB::table('users')
            ->leftJoin('report', 'report.id_user', '=', 'users.id')
            ->select(
                'users.name',
                'users.divisi',
                DB::raw('SUM(report.shalat_wajib) as shalat_wajib'),
                DB::raw('SUM(report.qiyamul_lail) as qiyamul_lail'),
                DB::raw('SUM(report.tilawah) as tilawah'),
                DB::raw('SUM(report.duha) as duha')
            )
            ->groupBy('users.id', 'users.name', 'users.divisi')
            ->get();

        // Debug apakah data ada atau tidak
        // dd($data);

        return Excel::download(new DailyReportExport($data), 'daily_report.xlsx');
    }
}
