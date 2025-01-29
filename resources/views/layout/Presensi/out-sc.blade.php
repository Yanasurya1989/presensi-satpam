@extends('layout.master')
@section('content')
    <div class="container-fluid">

        <?php
        $role = Auth::user()->role->name;
        ?>
        @if ($role == 'Super Admin' || $role == 'Admin')
            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800">Presensi Keluar</h1>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Presensi Disini</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col">
                            <form action="{{ url('/presensi-keluar/proses') }}" method="POST">
                                {{-- {{ csrf_field() }} --}}
                                @csrf
                                <div class="form-group">
                                    <center>
                                        <label id="clock"
                                            style="font-size: 100px; color: #659980; -webkit-text-stroke: 3px #02C39A;
                                                        text-shadow: 4px 4px 10px #CDE4B1,
                                                        4px 4px 20px rgba(210, 45, 26, 0.4),
                                                        4px 4px 30px rgba(210, 25, 16, 0.4),
                                                        4px 4px 40px rgba(210, 15, 06, 0.4);">
                                        </label>
                                    </center>
                                </div>
                                <center>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-danger">Klik Untuk Presensi
                                            Keluar</button>
                                    </div>
                                </center>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <p>Anda tidak bisa mengakses halaman ini</p>
        @endif
    </div>
@endsection

<script src="{{ asset('admin/js/scriptkuring.js') }}"></script>
