@extends('layout.master')

@section('content')
    <div class="container">
        <h2 class="mb-4">Rekap Presensi Semua User</h2>

        <form action="{{ route('presence.rekapAdmin') }}" method="GET" class="row mb-3">
            <div class="col-md-3">
                <label for="month">Bulan (mulai 25):</label>
                <select name="month" id="month" class="form-control">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('month', $month ?? '') == $m ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <label for="year">Tahun:</label>
                <select name="year" id="year" class="form-control">
                    @for ($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ request('year', $year ?? '') == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
            {{-- <form action="{{ route('rekap.export') }}" method="GET">
                <button type="submit" class="btn btn-success">Export Excel</button>
            </form> --}}

        </form>

        <form action="{{ route('rekap.export') }}" method="GET">
            <input type="number" name="month" value="{{ now()->month }}" hidden>
            <input type="number" name="year" value="{{ now()->year }}" hidden>
            <button type="submit" class="btn btn-success">Export Excel</button>
        </form><br>

        @if (request('month') && request('year'))
            <div class="alert alert-info">
                {{-- Menampilkan data presensi dari --}}
                {{ \Carbon\Carbon::createFromDate(request('year'), request('month'), 25)->format('d M Y') }}
                {{-- sampai --}}
                {{ \Carbon\Carbon::createFromDate(request('year'), request('month'), 25)->addMonth()->setDay(24)->format('d M Y') }}
            </div>
        @else
            <div class="alert alert-secondary">
                Menampilkan semua data presensi tanpa filter.
            </div>
        @endif

        <table class="table table-bordered table-striped mt-3">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jumlah Hari Hadir</th>
                    <th>Total Lembur</th>
                    <th>Photo</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rekap as $index => $data)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $data['user']->name }}</td>
                        <td>{{ $data['total_hadir'] }} Hari</td>
                        <td>
                            @php
                                $jam = floor($data['total_menit_lembur'] / 60);
                                $menit = $data['total_menit_lembur'] % 60;
                            @endphp
                            {{ $jam }} jam {{ $menit }} menit
                        </td>
                        <td>
                            {{-- <pre>{{ print_r($data, true) }}</pre> --}}
                            @if ($data['foto'])
                                <img src="{{ asset('storage/' . $data['foto']) }}" alt="Foto Presensi" width="80">
                            @else
                                <span class="text-muted">Tidak ada foto</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
