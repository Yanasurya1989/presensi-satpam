<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RekapPresensiExport implements FromCollection, WithHeadings
{
    protected $month;
    protected $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        return User::with([
            'presences' => function ($query) {
                $query->whereMonth('tgl_presensi', $this->month)
                    ->whereYear('tgl_presensi', $this->year);
            },
            'overtimes' => function ($query) {
                $query->whereMonth('start_time', $this->month)
                    ->whereYear('start_time', $this->year);
            }
        ])
            ->get()
            ->map(function ($user) {
                return [
                    'Nama' => $user->name,
                    'Jumlah Hari Hadir' => $user->presensi->count(),
                    'Total Lembur (Jam)' => round($user->overtimes->sum('total_minutes') / 60, 2),
                ];
            });
    }

    public function headings(): array
    {
        return ['Nama', 'Jumlah Hari Hadir', 'Total Lembur (Jam)'];
    }
}
