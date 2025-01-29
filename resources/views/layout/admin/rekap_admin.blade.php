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
                                <th>Nama</th>
                                <th>Shalat Wjib</th>
                                <th>Qiyamul Lail</th>
                                <th>Tilawah</th>
                                <th>Duha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rekap as $data)
                                <tr>
                                    <td>{{ $data->name }}</td>
                                    <td>{{ $data->qiyamul_lail }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
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
