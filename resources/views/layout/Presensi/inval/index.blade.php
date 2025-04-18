@extends('layout.master')

@section('content')
    <div class="container">
        <h3 class="mb-4">Rekap Inval Saya</h3>

        <div class="alert alert-info">
            Total Inval: <strong>{{ $totalInval }}</strong> kali
        </div>

        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-5">
                <label for="start" class="form-label">Tanggal Mulai</label>
                <input type="date" name="start" id="start" value="{{ request('start') }}" class="form-control">
            </div>
            <div class="col-md-5">
                <label for="end" class="form-label">Tanggal Akhir</label>
                <input type="date" name="end" id="end" value="{{ request('end') }}" class="form-control">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Waktu Mulai</th>
                    <th>Waktu Selesai</th>
                    <th>Nama yang Digantikan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($invals as $inval)
                    <tr>
                        <td>{{ Auth::user()->name }}</td>
                        <td>{{ $inval->created_at->format('d-m-Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($inval->time_start)->format('H:i') }}</td>
                        <td>{{ \Carbon\Carbon::parse($inval->time_end)->format('H:i') }}</td>
                        <td>{{ $inval->pengganti }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data inval.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
@endsection
