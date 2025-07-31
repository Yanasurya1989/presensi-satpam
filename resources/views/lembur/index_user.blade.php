@extends('layout.master')

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <h1>Rekap Lembur Saya</h1>

        {{-- Filter --}}
        <form method="GET" action="{{ route('lembur.index') }}" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <label for="from">Dari Tanggal</label>
                    <input type="date" name="from" id="from" class="form-control" value="{{ request('from') }}">
                </div>
                <div class="col-md-3">
                    <label for="to">Sampai Tanggal</label>
                    <input type="date" name="to" id="to" class="form-control" value="{{ request('to') }}">
                </div>
                <div class="col-md-3 align-self-end">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('lembur.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        {{-- Tombol Export --}}
        <form method="GET" action="{{ route('lembur.export') }}" class="mb-3">
            <input type="hidden" name="from" value="{{ request('from') }}">
            <input type="hidden" name="to" value="{{ request('to') }}">
            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
            <button type="submit" class="btn btn-success">Export Excel</button>
        </form>

        {{-- Total --}}
        <div class="alert alert-info">
            <strong>Total lembur:</strong> {{ formatMenit($totalLembur) }}
        </div>

        @php
            function formatMenit($menit)
            {
                if (!$menit) {
                    return '-';
                }
                $jam = intdiv($menit, 60);
                $sisa = $menit % 60;
                return "$jam jam $sisa menit";
            }

            function formatJam($waktu)
            {
                return $waktu ? \Carbon\Carbon::parse($waktu)->format('H:i') : '-';
            }
        @endphp

        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Tanggal</th>
                    <th>Mulai Lembur 1</th>
                    <th>Akhir Lembur 1</th>
                    <th>Mulai Lembur 2</th>
                    <th>Akhir Lembur 2</th>
                    <th>Mulai Lembur 3</th>
                    <th>Akhir Lembur 3</th>
                    <th>Total Lembur</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($lemburUser as $item)
                    <tr>
                        <td>{{ $item->tanggal }}</td>
                        <td>{{ formatJam($item->mulai_lembur_satu) }}</td>
                        <td>{{ formatJam($item->akhir_lembur_satu) }}</td>
                        <td>{{ formatJam($item->mulai_lembur_dua) }}</td>
                        <td>{{ formatJam($item->akhir_lembur_dua) }}</td>
                        <td>{{ formatJam($item->mulai_lembur_tiga) }}</td>
                        <td>{{ formatJam($item->akhir_lembur_tiga) }}</td>
                        <td>{{ $item->total_lembur }} menit ({{ formatMenit($item->total_lembur) }})</td>
                        <td>
                            <a href="{{ route('lembur.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>

                            <form action="{{ route('lembur.destroy', $item->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data lembur ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada data lembur ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
