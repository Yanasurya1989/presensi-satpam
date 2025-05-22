@extends('layout.master')

@section('content')
    <div class="container mt-4">
        <h3 class="mb-4">Rekap Presensi, Lembur, dan Inval</h3>

        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Jam Masuk</th>
                    <th>Jam Keluar</th>
                    <th>Shift</th>
                    <th>Pengganti (Inval)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rekap as $item)
                    <tr>
                        <td>{{ $item->nama }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                        <td>{{ $item->jenis }}</td>
                        <td>{{ $item->jam_in ? \Carbon\Carbon::parse($item->jam_in)->format('H:i') : '-' }}</td>
                        <td>{{ $item->jam_out ? \Carbon\Carbon::parse($item->jam_out)->format('H:i') : '-' }}</td>
                        <td>{{ $item->shift ?? '-' }}</td>
                        <td>{{ $item->pengganti ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
