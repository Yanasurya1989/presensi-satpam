@extends('layout.master')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Riwayat Lembur Saya</h1>

        <div class="card shadow mb-4">
            <div class="card-body">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th rowspan="2" class="align-middle text-center">Nama</th>
                            <th colspan="2">Shift 1</th>
                            <th colspan="2">Shift 2</th>
                            <th colspan="2">Shift 3</th>
                            <th rowspan="2" class="align-middle text-center">Total</th>
                        </tr>
                        <tr>
                            <th>Mulai</th>
                            <th>Selesai</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($groupedOvertime as $userName => $shifts)
                            <tr>
                                <td>{{ $userName }}</td>
                                @for ($i = 1; $i <= 3; $i++)
                                    <td>
                                        {{ isset($shifts[$i]['start_time']) && $shifts[$i]['start_time']
                                            ? \Carbon\Carbon::parse($shifts[$i]['start_time'])->format('H:i')
                                            : '-' }}
                                    </td>
                                    <td>
                                        {{ isset($shifts[$i]['end_time']) && $shifts[$i]['end_time']
                                            ? \Carbon\Carbon::parse($shifts[$i]['end_time'])->format('H:i')
                                            : '-' }}
                                    </td>
                                @endfor
                                <td>
                                    @php
                                        $totalMinutes = 0;
                                        foreach ($shifts as $shift) {
                                            $totalMinutes += $shift['total_minutes'] ?? 0;
                                        }
                                        $hours = floor($totalMinutes / 60);
                                        $minutes = $totalMinutes % 60;
                                    @endphp
                                    {{ $hours }} jam {{ $minutes }} menit
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">Belum ada data lembur.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
