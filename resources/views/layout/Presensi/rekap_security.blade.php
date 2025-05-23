@extends('layout.master')

@section('content')
    <div class="container">
        <h2 class="mb-4">Rekap Presensiku</h2>

        @if (request('start_date') && request('end_date'))
            <p><strong>Rentang Tanggal:</strong>
                {{ \Carbon\Carbon::parse(request('start_date'))->translatedFormat('d F Y') }}
                s.d.
                {{ \Carbon\Carbon::parse(request('end_date'))->translatedFormat('d F Y') }}
            </p>
        @endif


        {{-- <form action="{{ route('presence.rekap') }}" method="GET" class="row mb-3">
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
        </form> --}}

        <form action="{{ route('presence.rekap') }}" method="GET" class="row mb-3">
            <div class="col-md-3">
                <label for="start_date">Mulai Tanggal:</label>
                <input type="date" name="start_date" id="start_date" class="form-control"
                    value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label for="end_date">Sampai Tanggal:</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </form>

        @if (request('start_date') && request('end_date'))
            <div class="mb-3">
                <a href="{{ route('presence.export', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                    class="btn btn-success">
                    Export Excel
                </a>
            </div>
        @endif

        {{-- <h5>Jumlah Hari Hadir: {{ $totalHariPerUser[auth()->id()] ?? 0 }} Hari</h5> --}}

        <h5>Jumlah Hari Hadir:
            @php
                $userId = auth()->id();
                $jumlahHari = $totalHariPerUser[$userId] ?? 0;

                // Jika user ini adalah user khusus (misalnya ID 5)
                $userKhususIds = [30]; // <-- isi dengan ID user yang perlu dihitung setengah
                if (in_array($userId, $userKhususIds)) {
                    $jumlahHari = $jumlahHari / 2;
                }
            @endphp
            {{ $jumlahHari }} Hari
        </h5>

        <p><strong>Total lembur bulan ini:</strong>
            @php
                $totalMenitLembur = $lembur->flatten()->sum('total_minutes');
                $jam = floor($totalMenitLembur / 60);
                $menit = $totalMenitLembur % 60;
            @endphp
            {{ $jam }} jam {{ $menit }} menit
        </p>

        {{-- <p><strong>Total lembur bulan ini:</strong>
            {{ $jam }} jam {{ $menit }} menit
        </p> --}}

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
                    @if (auth()->id() == 30)
                        <th>Jam ke-1</th>
                        <th>Jam ke-2</th>
                    @else
                        <th>Jam</th>
                    @endif
                    <th>Lama Lembur</th>
                    <th>Photo</th>
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

                        $jamShift = explode(',', $presence->jam); // contoh: ['06:30:00', '14:30:00']
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($presence->tanggal)->translatedFormat('d F Y') }}</td>

                        @if (auth()->id() == 30)
                            <td>{{ $jamShift[0] ?? '-' }}</td>
                            <td>{{ $jamShift[1] ?? '-' }}</td>
                        @else
                            <td>{{ $presence->jam }}</td>
                        @endif

                        <td>
                            @if ($totalMenitLembur > 0)
                                {{ $jam }} jam {{ $menit }} menit
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <img src="{{ $presence->photo }}" alt="Foto Presensi" style="max-width: 200px;">
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada presensi di bulan ini.</td>
                    </tr>
                @endforelse
            </tbody>

        </table>

    </div>
@endsection
