@extends('layout.master')
@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">Daily Report</h1>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tambah Data Daily Report</h6>
            </div>
            <div class="card-body">

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <a href="{{ route('report.export') }}" class="btn btn-success mb-3">Export Excel</a>

                <div class="table-responsive">
                    <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="text-center">
                                <th>Nama</th>
                                <th>Divisi</th> <!-- Tambahkan kolom divisi -->
                                <th>Shalat Wajib</th>
                                <th>Qiyamul Lail</th>
                                <th>Tilawah</th>
                                <th>Duha</th>
                                <th>Mendoakan Siswa</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($users && count($users) > 0)
                                @foreach ($users as $data)
                                    <tr>
                                        <td>{{ $data->name }}</td>
                                        <td>{{ $data->divisi ?? '-' }}</td>
                                        <!-- Tampilkan divisi, jika null tampilkan "-" -->
                                        <td>{{ number_format($data->report_sum_shalat_wajib) }}</td>
                                        <td>{{ number_format($data->report_sum_qiyamul_lail) }}</td>
                                        <td>{{ number_format($data->report_sum_tilawah) }}</td>
                                        <td>{{ number_format($data->report_sum_duha) }}</td>
                                        <td>{{ number_format($data->report_sum_mendoakan_siswa) }}</td>
                                        <td>
                                            <form action="{{ route('report.delete', $data->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center">Data tidak tersedia</td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Max</th>
                                <th>-</th> <!-- Tambahkan kolom kosong untuk divisi -->
                                <th>{{ number_format($users->max('report_sum_shalat_wajib')) }}</th>
                                <th>{{ number_format($users->max('report_sum_qiyamul_lail')) }}</th>
                                <th>{{ number_format($users->max('report_sum_tilawah')) }}</th>
                                <th>{{ number_format($users->max('report_sum_duha')) }}</th>
                                <th>{{ number_format($users->max('report_sum_mendoakan_siswa')) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection

<script src="{{ asset('admin/js/scriptkuring.js') }}"></script>
