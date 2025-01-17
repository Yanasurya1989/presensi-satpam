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
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama</th>
                                <th>Shalat Wjib</th>
                                <th>Qiyamul Lail</th>
                                <th>Tilawah</th>
                                <th>Duha</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($report as $data)
                                <tr>

                                    <td>{{ $data['tanggal'] }}</td>
                                    <td>{{ $data->user->name }}</td>
                                    <td>{{ $data['shalat_wajib'] }}</td>
                                    <td>{{ $data['qiyamul_lail'] }}</td>
                                    <td>{{ $data['tilawah'] }}</td>
                                    <td>{{ $data['duha'] }}</td>
                                    <td>
                                        <a href="{{ url('/study/delete', encrypt($data['id'])) }}" class="btn btn-danger"
                                            onclick="return confirm('Yakin akan dihapus?')">Hapus</a>

                                        <a href="{{ url('/study/delete', encrypt($data['id'])) }}" class="btn btn-warning"
                                            onclick="return confirm('Yakin akan dihapus?')">Update</a>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="{{ asset('admin/js/scriptkuring.js') }}"></script>
