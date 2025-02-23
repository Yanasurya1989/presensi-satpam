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
                            <form action="{{ url('presensi/kirim_hadir') }}" method="POST">
                                {{-- {{ csrf_field() }} --}}
                                @csrf
                                <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
                                <div class="form-group">
                                    <center>
                                        <label id="clock"
                                            style="font-size: 100px; color: #0A77DE; -webkit-text-stroke: 3px #00ACFE;
                                                    text-shadow: 4px 4px 10px #36D6FE,
                                                    4px 4px 20px #36D6FE,
                                                    4px 4px 30px#36D6FE,
                                                    4px 4px 40px #36D6FE;">
                                        </label>
                                    </center>
                                </div>
                                <center>
                                    <div class="col mb-3">
                                        {{-- <input type="hidden" id="lokasi"> --}}
                                        <input type="text" id="lokasi" name="lokasi">
                                        <input type="text" id="image" name="image">

                                        <div class="webcam-capture"></div>

                                    </div>
                                    <div class="form-group">
                                        @if ($cek > 0)
                                            <button type="submit" class="btn btn-danger" id="take-absen">Klik Untuk
                                                Presensi Pulang
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-primary" id="take-absen">Klik Untuk
                                                Presensi Masuk

                                            </button>
                                        @endif


                                    </div>
                                    <div class="col">
                                        <div id="map"></div>
                                    </div>

                                    {{-- audio --}}
                                    {{-- audio --}}
                                    <audio id="notifikasi_in">
                                        <source src="{{ asset('admin/sound/notifikasi-in.mp3') }}" type="audio/mpeg">
                                    </audio>

                                    <audio id="notifikasi_out">
                                        <source src="{{ asset('admin/sound/notice-in.mp3') }}" type="audio/mpeg">
                                    </audio>

                                    <audio id="radius_sound">
                                        <source src="{{ asset('admin/sound/radius.mp3') }}" type="audio/mpeg">
                                    </audio>
                                </center>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <p>Presensi belum tersedia</p>
        @endif
    </div>
@endsection

<script src="{{ asset('admin/js/scriptkuring.js') }}"></script>

@push('myscript')
    <script>
        var notifikasi_in = document.getElementById('notifikasi_in');
        var notifikasi_out = document.getElementById('notifikasi_out');
        var radius_sound = document.getElementById('radius_sound');
        Webcam.set({
            height: 280,
            width: 440,
            image_format: 'jpeg',
            jpeg_quality: 80
        });

        Webcam.attach('.webcam-capture')

        // Webcam.snap(function(uri) {
        //     image = uri;
        //     document.getElementById('image').value = image;
        // });

        var lokasi = document.getElementById('lokasi');

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);

        }

        function successCallback(position) {
            lokasi.value = position.coords.latitude + "," + position.coords.longitude;
            var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 13);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 20,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);
            var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
            var circle = L.circle([-6.971690, 107.752041], {
                color: 'blue',
                fillColor: 'red',
                fillOpacity: 0.5,
                // radius dalam satuan meter yang berarti 20 meter
                radius: 20
            }).addTo(map);
        }

        function errorCallback() {

        }

        $(document).ready(() => {



            // $('#take-absen').click(function(e) {
            //     Webcam.snap(function(uri) {
            //         image = uri;
            //     });

            //     // alert(image);
            //     var lokasi = $('#lokasi').val();

            //     $.ajax({
            //         type: 'POST',
            //         url: '/presensi/kirim_hadir',
            //         data: {
            //             _token: "{{ csrf_token() }}",
            //             image: image,
            //             lokasi: lokasi
            //         },
            //         cache: false,
            //         success: function(respond) {
            //             var status = respond.split("|");
            //             if (status[0] == 'success') {
            //                 if (status[2] == 'in') {
            //                     notifikasi_in.play();
            //                 } else {
            //                     notifikasi_out.play();
            //                 }
            //                 Swal.fire({
            //                     title: 'Success!',
            //                     text: status[1],
            //                     icon: 'success',
            //                     confirmButtonText: 'OK'
            //                 })
            //                 setTimeout("location.href='presensi-keluar'", 3000);
            //             } else {
            //                 if (status[2] == 'radius') {
            //                     radius_sound.play();
            //                 }
            //                 Swal.fire({
            //                     title: 'Error!',
            //                     text: status[1],
            //                     icon: 'error',
            //                 })
            //             }
            //         }
            //     });
            // })

        })
    </script>
@endpush
