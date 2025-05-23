<?php

namespace App\Exports;

use App\Models\Presence;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PresenceExport implements FromView
{
    protected $start_date;
    protected $end_date;

    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function view(): View
    {
        $presences = Presence::whereBetween('tanggal', [$this->start_date, $this->end_date])
            ->where('user_id', auth()->id())
            ->get();

        return view('exports.presence', [
            'presences' => $presences,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);
    }
}
