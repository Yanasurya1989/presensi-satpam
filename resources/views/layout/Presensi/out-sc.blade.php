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
                                    <div class="col mb-3">
                                        <input type="hidden" id="lokasi">
                                        {{-- <input type="text" id="lokasi"> --}}
                                        <div class="webcam-capture"></div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-danger">Klik Untuk Presensi
                                            Keluar</button>
                                    </div>
                                    <div class="col">
                                        <div id="map"></div>
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
@push('myscript')
    <script>
        Webcam.set({
            height: 280,
            width: 440,
            image_format: 'jpeg',
            jpeg_quality: 80
        });

        Webcam.attach('.webcam-capture')

        var lokasi = document.getElementById('lokasi');
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);

        }

        function successCallback(position) {
            lokasi.value = position.coords.latitude + "," + position.coords.longitude;
            var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 13);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);
            var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
            var circle = L.circle([position.coords.latitude, position.coords.longitude], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: 500
            }).addTo(map);
        }

        function errorCallback() {

        }
    </script>
@endpush
