@extends('layout.master')

@section('content')
    <div class="container">
        <h2 class="mb-4">Rekap Presensiku</h2>

        <form action="{{ route('presence.rekap') }}" method="GET" class="row mb-3">
            <div class="col-md-3">
                <label for="month">Bulan (mulai 25):</label>
                <select name="month" id="month" class="form-control">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}"
                            {{ request('month', $month ?? now()->month) == $m ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <label for="year">Tahun:</label>
                <select name="year" id="year" class="form-control">
                    @for ($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}"
                            {{ request('year', $year ?? now()->year) == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </form>

        <h5>Jumlah Hari Hadir: {{ $totalHariPerUser[auth()->id()] ?? 0 }} Hari</h5>
        <p><strong>Total lembur bulan ini:</strong>
            @php
                $totalMenitLembur = $lembur->flatten()->sum('total_minutes');
                $jam = floor($totalMenitLembur / 60);
                $menit = $totalMenitLembur % 60;
            @endphp
            {{ $jam }} jam {{ $menit }} menit
        </p>


        {{-- <table class="table table-bordered table-striped mt-3">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $userPresences = $presences->where('user_id', auth()->id());
                @endphp
                @forelse ($userPresences as $index => $presence)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($presence->tanggal)->translatedFormat('d F Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($presence->jam)->format('H:i:s') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">Belum ada presensi di bulan ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table> --}}

        <table class="table table-bordered table-striped mt-3">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Lama Lembur</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $userPresences = $presences->where('user_id', auth()->id());
                @endphp
                @forelse ($userPresences as $index => $presence)
                    @php
                        $tanggalPresensi = \Carbon\Carbon::parse($presence->tanggal)->format('Y-m-d');
                        $lemburHariItu = $lembur[$tanggalPresensi] ?? null;

                        // Hitung total menit lembur hari itu
                        $totalMenitLembur = $lemburHariItu ? $lemburHariItu->sum('total_minutes') : 0;
                        $jam = floor($totalMenitLembur / 60);
                        $menit = $totalMenitLembur % 60;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($presence->tanggal)->translatedFormat('d F Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($presence->jam)->format('H:i:s') }}</td>
                        <td>
                            @if ($totalMenitLembur > 0)
                                {{ $jam }} jam {{ $menit }} menit
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada presensi di bulan ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
@endsection
