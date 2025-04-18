@extends('layout.master') {{-- Sesuaikan jika layout-nya beda --}}

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Input Lembur</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Form Lembur</h6>
            </div>
            <div class="card-body">
                {{-- <form action="{{ route('lembur.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Nama Pegawai</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    </div>

                    <div class="form-group">
                        <label for="start_time">Jam Mulai</label>
                        <input type="datetime-local" name="start_time" id="start_time" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="end_time">Jam Selesai</label>
                        <input type="datetime-local" name="end_time" id="end_time" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
                </form> --}}

                <form method="POST" action="{{ route('lembur.store') }}">
                    @csrf

                    <!-- Nama User (readonly) -->
                    <div class="mb-3">
                        <label for="user_name" class="form-label">Nama</label>
                        <input type="text" id="user_name" class="form-control" value="{{ Auth::user()->name }}" readonly>
                    </div>

                    <!-- Jadwal Shift Hari Ini -->
                    <div class="mb-3">
                        <label for="jadwal_shift" class="form-label">Jadwal Shift Hari Ini</label>
                        <input type="text" id="jadwal_shift" class="form-control"
                            value="{{ $jadwalShift ? $jadwalShift->shift->name . ' (' . $jadwalShift->shift->start_time . ' - ' . $jadwalShift->shift->end_time . ')' : 'Tidak ada jadwal shift' }}"
                            readonly>
                    </div>

                    <!-- ID User (hidden) -->
                    <input type="hidden" name="user_id" value="{{ Auth::id() }}">

                    <!-- Shift -->
                    <input type="hidden" name="shift" value="{{ $shift }}">


                    <!-- ID User (hidden) -->
                    <input type="hidden" name="user_id" value="{{ Auth::id() }}">

                    <!-- Shift -->
                    <input type="hidden" name="shift" value="{{ $shift }}">

                    <!-- Start Time -->
                    <div class="mb-3">
                        <label for="start_time" class="form-label">Start Time</label>
                        <input type="datetime-local" name="start_time" id="start_time" class="form-control" required>
                    </div>

                    <!-- End Time -->
                    <div class="mb-3">
                        <label for="end_time" class="form-label">End Time</label>
                        <input type="datetime-local" name="end_time" id="end_time" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Lembur Shift {{ $shift }}</button>
                </form>


            </div>
        </div>
    </div>
@endsection
