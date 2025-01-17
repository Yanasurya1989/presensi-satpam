@extends('layout.master')
@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">Selamat datang {{ Auth::user()->name }}, anda login sebagai
            {{ Auth::user()->role->name }}</h1>

        {{-- <a href="{{ url('/insert') }}" class="btn btn-primary form-control">Insert Mutaba'ah</a> --}}
        <a href="#" class="btn btn-primary form-control" id="presensi" onclick="presensi()">Presensi Kedatangan</a>
        <p id="result" class="text-danger">Anda belum presensi</p>



        {{-- {{ Auth::user() }} --}}
        {{-- {{ Auth::user()->role }} --}}
    </div>
    <script>
        $(document).ready(function() {
            // Set CSRF Token in Header
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            // Handle Form Submission
            $("#presensi").click(function(e) {
                e.preventDefault(); // Prevent default form submission

                let name = $("#name").val();

                $.ajax({
                    url: "/submit", // Replace with your route
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
@endsection
