@extends('layout.master')

@section('content')
    <div class="container">
        <h2 class="mb-4">Rekap Presensi Harian</h2>

        <form action="{{ route('rekap.harian') }}" method="GET" class="row mb-3">
            <div class="col-md-3">
                <label for="tanggal">Tanggal:</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ $tanggal }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </form>

        <div class="alert alert-info">
            Menampilkan data presensi dan lembur untuk tanggal:
            <strong>{{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</strong>
        </div>

        <table class="table table-bordered table-striped mt-3">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>Jam Masuk</th>
                    <th>Jam Keluar</th>
                    <th>Foto Masuk</th>
                    <th>Foto Keluar</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($presences as $index => $presensi)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $presensi->jam_masuk ?? '-' }}</td>
                        <td>{{ $presensi->jam_keluar ?? '-' }}</td>
                        <td>
                            @if ($presensi->poto_masuk)
                                <img src="{{ asset('storage/' . $presensi->poto_masuk) }}" alt="Foto Masuk" width="80">
                            @else
                                <span class="text-muted">Tidak ada</span>
                            @endif
                        </td>
                        <td>
                            @if ($presensi->poto_keluar)
                                <img src="{{ asset('storage/' . $presensi->poto_keluar) }}" alt="Foto Keluar"
                                    width="80">
                            @else
                                <span class="text-muted">Tidak ada</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data presensi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <h4 class="mt-5">Data Lembur</h4>
        <table class="table table-bordered table-striped mt-2">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>Jam Mulai</th>
                    <th>Jam Selesai</th>
                    <th>Total Lembur</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($lembur as $index => $data)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $data->start_time }}</td>
                        <td>{{ $data->end_time }}</td>
                        <td>
                            @php
                                $jam = floor($data->total_minutes / 60);
                                $menit = $data->total_minutes % 60;
                            @endphp
                            {{ $jam }} jam {{ $menit }} menit
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data lembur.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
