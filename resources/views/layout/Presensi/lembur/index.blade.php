@extends('layout.master')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Riwayat Lembur Saya</h1>

        <div class="card shadow mb-4">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Total Menit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($overtimes as $overtime)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($overtime->start_time)->format('d-m-Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($overtime->start_time)->format('H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($overtime->end_time)->format('H:i') }}</td>
                                <td>{{ floor($overtime->total_minutes / 60) }} jam {{ $overtime->total_minutes % 60 }} menit
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">Belum ada data lembur.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
