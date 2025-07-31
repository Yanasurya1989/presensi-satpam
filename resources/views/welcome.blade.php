{{-- @extends('layout.master')
@section('content')
    <div class="container-fluid">

        Page Heading
        <h1 class="h3 mb-4 text-gray-800">Selamat datang {{ Auth::user()->name }}, anda login sebagai
            {{ Auth::user()->role->name }}</h1>

        <a href="{{ url('/insert') }}" class="btn btn-primary form-control">Insert Mutaba'ah</a>
        <a href="#" class="btn btn-primary form-control" id="presensi" onclick="presensi()">Presensi Kedatangan</a>
        <p id="result" class="text-danger">Anda belum presensi</p>



        {{ Auth::user() }}
        {{ Auth::user()->role }}
    </div>
    <script>
        $(document).ready(function() {
            
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            
            $("#presensi").click(function(e) {
                e.preventDefault(); 

                let name = $("#name").val();

                $.ajax({
                    url: "/submit", 
                    type: "POST",
                    data: {
                        name: name,
                    },
                    success: function(response) {
                        $("#response").html(`<p>${response.message}</p>`);
                    },
                    error: function(xhr) {
                        $("#response").html(
                            `<p>Error: ${xhr.responseJSON.message}</p>`
                        );
                    },
                });
            });
        });
    </script>
@endsection --}}


@extends('layout.master')
@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800 text-center">
            Selamat datang {{ Auth::user()->name }}, anda login sebagai {{ Auth::user()->role->name }}
        </h1>

        {{-- tes update --}}
        <p>tes</p>

        <!-- Menu Section -->
        <div class="row">
            @if (Auth::user()->role->name == 'Super Admin' || Auth::user()->role->name == 'Kabid 4')
                <div class="col-md-4">
                    <a href="{{ url('/users') }}" class="btn btn-primary btn-lg btn-block">
                        <i class="fas fa-user-plus"></i> Tambah User
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ url('/shiftsforschedule') }}" class="btn btn-success btn-lg btn-block">
                        <i class="fas fa-cogs"></i> Setting Shift
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ url('/shift-assignment') }}" class="btn btn-warning btn-lg btn-block">
                        <i class="fas fa-calendar-alt"></i> Atur Jadwal Shift
                    </a>
                </div>
            @elseif(Auth::user()->role->name == 'Scurity')
                <div class="col-12">
                    {{-- <a href="{{ url('/presensi-sc') }}" --}}
                    <a href="{{ url('/view_presence') }}"
                        class="btn btn-info btn-lg btn-block d-flex align-items-center justify-content-center"
                        style="height: 100px; font-size: 24px;">
                        <i class="fas fa-check-circle"></i> <span class="ml-2">Presensi</span>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });
        });
    </script>
@endsection
