@extends('layout.master')

@section('content')
    <div class="container">
        <h1>Rekap Lembur Bulan Ini</h1>

        <h3>Total Lembur: {{ gmdate("H \j\a\m i \m\e\n\i\t", $totalLembur * 60) }}</h3>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Mulai</th>
                    <th>Akhir</th>
                    <th>Durasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lemburUser as $item)
                    <tr>
                        <td>{{ $item->tanggal }}</td>
                        <td>{{ $item->jam_mulai }}</td>
                        <td>{{ $item->jam_selesai }}</td>
                        <td>{{ gmdate("H \j\a\m i \m\e\n\i\t", $item->total_lembur * 60) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection
