@extends('layout.master')

@section('content')
    <div class="container">
        <h1>Rekap Lembur Bulan Ini</h1>

        {{-- Filter start --}}
        @if ($role === 'Kabid 4')
            <form method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <label>Dari Tanggal</label>
                        <input type="date" name="from" value="{{ $request->from }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Sampai Tanggal</label>
                        <input type="date" name="to" value="{{ $request->to }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Nama User</label>
                        <select name="user_id" class="form-control">
                            <option value="">-- Semua User --</option>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}" {{ $request->user_id == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary mr-2">Filter</button>
                        <a href="{{ route('lembur.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        @endif

        {{-- Filter end --}}

        {{-- Total start --}}
        @if ($role === 'Kabid 4')
            <div class="alert alert-success">
                <strong>Total lembur hasil filter:</strong> {{ formatMenit($totalLemburMenit) }}
            </div>
        @endif
        {{-- Total end --}}

        {{-- export start --}}
        @if ($role === 'Kabid 4')
            <a href="{{ route('lembur.export', request()->all()) }}" class="btn btn-success mb-3">
                Export Excel
            </a>
        @endif
        {{-- export end --}}

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

        @if ($role === 'Kabid 4')
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Nama User</th>
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
                    @forelse ($semuaLembur as $item)
                        <tr>
                            <td>{{ $item->user->name ?? '-' }}</td>
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

                                <form action="{{ route('lembur.destroy', $item->id) }}" method="POST"
                                    style="display: inline-block;"
                                    onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">Belum ada data lembur bulan ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @else
            {{-- BAGIAN USER BIASA (opsional, tetap ditampilkan kalau kamu butuh) --}}
        @endif
    </div>
@endsection
