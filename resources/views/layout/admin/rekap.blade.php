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

                <div class="table-responsive">
                    <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="text-center">
                                <th>Tanggal</th>
                                <th>Nama</th>
                                <th>Shalat Wjib</th>
                                <th>Qiyamul Lail</th>
                                <th>Tilawah</th>
                                <th>Duha</th>
                                {{-- <th>Aksi</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_shalat_wajib = 0;
                            $total_qiyamul_lail = 0;
                            $total_tilawah = 0;
                            $total_duha = 0;
                            ?>
                            @foreach ($report as $data)
                                {{ $total_shalat_wajib += $data['shalat_wajib'] }}
                                {{ $total_qiyamul_lail += $data['qiyamul_lail'] }}
                                {{ $total_tilawah += $data['tilawah'] }}
                                {{ $total_duha += $data['duha'] }}
                                <tr>
                                    <td>{{ $data['tanggal'] }}</td>
                                    <td>{{ $data->user->name }}</td>
                                    <td>{{ $data['shalat_wajib'] }}</td>
                                    <td>{{ $data['qiyamul_lail'] }}</td>
                                    <td>{{ $data['tilawah'] }}</td>
                                    <td>{{ $data['duha'] }}</td>
                                    {{-- <td>
                                        <a href="{{ url('/study/delete', encrypt($data['id'])) }}" class="btn btn-danger"
                                            onclick="return confirm('Yakin akan dihapus?')">Hapus</a>

                                        <a href="{{ url('/study/delete', encrypt($data['id'])) }}" class="btn btn-warning"
                                            onclick="return confirm('Yakin akan dihapus?')">Update</a>
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <th colspan="2" class="text-center">Jumlah</th>
                            <th>{{ $total_shalat_wajib }}</th>
                            <th>{{ $total_qiyamul_lail }}</th>
                            <th>{{ $total_tilawah }}</th>
                            <th>{{ $total_duha }}</th>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="{{ asset('admin/js/scriptkuring.js') }}"></script>
