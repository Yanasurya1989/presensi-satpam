@extends('layout.master')

@section('content')
    <div class="container mt-4">
        <h3 class="mb-4">Rekap Pivot Presensi, Lembur, dan Inval</h3>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Presensi Masuk</th>
                    <th>Shift1</th>
                    <th>Shift2</th>
                    <th>Shift3</th>
                    <th>Inval</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dataGabungan as $row)
                    <tr>
                        <td>{{ $row['nama'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($row['tanggal'])->format('d-m-Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($row['presensi_masuk'])->format('H:i') }}</td>
                        <td>{{ $row['lembur_shift1'] }}</td>
                        <td>{{ $row['lembur_shift2'] }}</td>
                        <td>{{ $row['lembur_shift3'] }}</td>
                        <td>{{ $row['inval'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Data kosong</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
