@extends('layout.master')
@section('content')
    <div class="container-fluid">

        <?php
        $role = Auth::user()->role->name;
        ?>
        @if ($role == 'Super Admin' || $role == 'Admin')
            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800">Presensi Karyawan</h1>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Presensi Disini</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col">
                            @if (session('success'))
                                <p style="color: green;">{{ session('success') }}</p>
                            @endif
                            <form action="{{ url('simpan-masuk') }}" method="POST">
                                {{-- {{ csrf_field() }} --}}
                                @csrf
                                <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
                                <div class="form-group">
                                    <p>Presensi berhasil!</p>
                                    <p>Waktu: {{ $attendance->created_at }}</p>
                                    <img src="{{ asset('storage/' . $attendance->photo) }}" width="100">

                                </div>
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
