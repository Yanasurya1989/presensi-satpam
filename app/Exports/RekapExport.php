<?php

namespace App\Exports;

use App\Models\Report;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RekapExport implements FromView
{
    protected $month, $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function view(): View
    {
        $users = User::withSum(['reports as report_sum_shalat_wajib' => function ($query) {
            $query->whereMonth('created_at', $this->month)->whereYear('created_at', $this->year);
        }], 'shalat_wajib')
            ->withSum(['reports as report_sum_qiyamul_lail' => function ($query) {
                $query->whereMonth('created_at', $this->month)->whereYear('created_at', $this->year);
            }], 'qiyamul_lail')
            ->withSum(['reports as report_sum_tilawah' => function ($query) {
                $query->whereMonth('created_at', $this->month)->whereYear('created_at', $this->year);
            }], 'tilawah')
            ->withSum(['reports as report_sum_duha' => function ($query) {
                $query->whereMonth('created_at', $this->month)->whereYear('created_at', $this->year);
            }], 'duha')
            ->get();

        return view('exports.rekap', compact('users'));
    }
}
