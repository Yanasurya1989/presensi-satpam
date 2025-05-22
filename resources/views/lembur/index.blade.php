@extends('layout.master')

@section('content')
    <div class="container">
        <h1>Data Lembur</h1>

        @php
            function formatMenit($menit)
            {
                $jam = intdiv($menit, 60);
                $sisaMenit = $menit % 60;
                return "$jam jam $sisaMenit menit";
            }

            use App\Models\Lembur;

            $totalLemburBulanIni = Lembur::where('user_id', auth()->id())
                ->whereMonth('tanggal', now()->month)
                ->sum('total_lembur');
        @endphp

        <div class="alert alert-info">
            <strong>Total lembur bulan ini:</strong> {{ formatMenit($totalLemburBulanIni) }}
        </div>

        <a href="{{ route('lembur.create') }}" class="btn btn-primary mb-3">Tambah Data</a>
        {{-- <p>User login ID: {{ auth()->id() }}</p>
        <p>Jumlah lembur: {{ $lemburs->count() }}</p> --}}
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama User</th>
                    <th>Tanggal</th>
                    <th>Mulai 1</th>
                    <th>Akhir 1</th>
                    <th>Mulai 2</th>
                    <th>Akhir 2</th>
                    <th>Mulai 3</th>
                    <th>Akhir 3</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lemburs as $lembur)
                    <tr>
                        <td>{{ $lembur->user->name ?? '-' }}</td>
                        <td>{{ $lembur->tanggal }}</td>
                        <td>{{ $lembur->mulai_lembur_satu }}</td>
                        <td>{{ $lembur->akhir_lembur_satu }}</td>
                        <td>{{ $lembur->mulai_lembur_dua }}</td>
                        <td>{{ $lembur->akhir_lembur_dua }}</td>
                        <td>{{ $lembur->mulai_lembur_tiga }}</td>
                        <td>{{ $lembur->akhir_lembur_tiga }}</td>
                        <td>{{ formatMenit($lembur->total_lembur) }}</td>
                        <td>
                            <a href="{{ route('lembur.edit', $lembur->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('lembur.destroy', $lembur->id) }}" method="POST"
                                style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
