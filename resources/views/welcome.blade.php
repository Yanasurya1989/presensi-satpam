@extends('layout.master')
@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">Selamat datang {{ Auth::user()->email }}, anda login sebagai
            {{ Auth::user()->role->name }}</h1>

        {{-- <a href="{{ url('/insert') }}" class="btn btn-primary form-control">Insert Mutaba'ah</a> --}}
        <a href="#" class="btn btn-primary form-control" id="presensi" onclick="presensi()">Presensi Kedatangan</a>
        <p id="result" class="text-danger">Anda belum presensi</p>

        {{-- {{ Auth::user() }} --}}
        {{-- {{ Auth::user()->role }} --}}
    </div>
@endsection

<script src="{{ asset('admin/js/scriptkuring.js') }}"></script>
