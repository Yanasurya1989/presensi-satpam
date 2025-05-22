<?php

namespace App\Exports;

use App\Models\Lembur;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LemburExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data)->map(function ($item) {
            return [
                $item->user->name ?? '-',
                $item->tanggal,
                $item->mulai_lembur_satu,
                $item->akhir_lembur_satu,
                $item->mulai_lembur_dua,
                $item->akhir_lembur_dua,
                $item->mulai_lembur_tiga,
                $item->akhir_lembur_tiga,
                $item->total_lembur,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Tanggal',
            'Mulai Lembur 1',
            'Akhir Lembur 1',
            'Mulai Lembur 2',
            'Akhir Lembur 2',
            'Mulai Lembur 3',
            'Akhir Lembur 3',
            'Total Lembur (menit)',
        ];
    }
}
