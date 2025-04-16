@extends('layout.master')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h2 class="mb-4">Presensi Kehadiran</h2>

                        {{-- Flash Message --}}
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        {{-- Logika Shift & Presensi --}}
                        @if (isset($hasShiftToday) && $hasShiftToday)
                            @if ($presensiHariIni)
                                <div class="alert alert-success">
                                    Anda sudah presensi pada jam
                                    {{ \Carbon\Carbon::parse($presensiHariIni->jam)->format('H:i') }}.
                                </div>
                            @else
                                <form action="{{ route('presence.store') }}" method="POST"
                                    onsubmit="return handleSubmit(event)">
                                    @csrf

                                    <div class="mb-3 text-start">
                                        <label for="video" class="form-label">Preview Kamera</label>
                                        <div class="ratio ratio-4x3 rounded overflow-hidden border">
                                            <video id="video" autoplay style="width: 100%; height: auto;"></video>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <img id="preview" style="display:none; max-width: 100%; margin-top: 10px;"
                                            class="rounded border" />
                                    </div>

                                    <input type="hidden" name="photo" id="photoInput">
                                    <input type="hidden" name="tipe" value="masuk">

                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary w-100" id="submitBtn" disabled>
                                            Klik Untuk Presensi Kedatangan
                                        </button>
                                    </div>
                                </form>
                            @endif
                        @elseif (isset($hasShiftToday) && !$hasShiftToday)
                            <div class="alert alert-warning">
                                Anda belum dijadwalkan shift hari ini.
                            </div>
                        @else
                            <div class="alert alert-danger">
                                Data shift tidak ditemukan.
                            </div>
                        @endif

                        {{-- Tombol Lembur selalu muncul --}}
                        <div class="mt-4">
                            <a href="{{ route('lembur.create') }}" class="btn btn-outline-success w-100">
                                Input Lembur
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const video = document.getElementById('video');
        const canvas = document.createElement('canvas');
        const photoInput = document.getElementById('photoInput');
        const preview = document.getElementById('preview');
        const submitBtn = document.getElementById('submitBtn');

        // Fungsi untuk ambil snapshot otomatis
        function takeSnapshotAutomatically() {
            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const context = canvas.getContext('2d');
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                const imageData = canvas.toDataURL('image/png');
                photoInput.value = imageData;
                preview.src = imageData;
                preview.style.display = 'block';

                // Aktifkan tombol submit setelah foto berhasil diambil
                submitBtn.disabled = false;
                console.log('Foto berhasil diambil otomatis.');
            } else {
                // Ulangi pengecekan jika video belum siap
                setTimeout(takeSnapshotAutomatically, 300);
            }
        }

        // Akses kamera dan jalankan otomatis ambil foto
        navigator.mediaDevices.getUserMedia({
                video: true
            })
            .then(stream => {
                video.srcObject = stream;
                video.onloadedmetadata = () => {
                    setTimeout(takeSnapshotAutomatically, 1000); // Tunggu sebentar agar kamera siap
                };
            })
            .catch(err => {
                alert("Tidak bisa mengakses kamera: " + err.message);
            });

        // Validasi saat submit
        function handleSubmit(e) {
            if (!photoInput.value) {
                e.preventDefault();
                alert("Foto belum berhasil diambil.");
                return false;
            }
            return true;
        }
    </script>
@endpush
