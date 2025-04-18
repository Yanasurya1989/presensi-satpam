@extends('layout.master')

@section('content')
    <div class="container">
        <h2 class="mb-4">Form Input Inval</h2>

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

        <form action="{{ route('inval.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="time_start" class="form-label">Waktu Mulai</label>
                <input type="time" class="form-control" name="time_start" required>
            </div>

            <div class="mb-3">
                <label for="time_end" class="form-label">Waktu Selesai</label>
                <input type="time" class="form-control" name="time_end" required>
            </div>

            <div class="mb-3">
                <label for="pengganti" class="form-label">Nama yang Digantikan</label>
                <select name="pengganti" class="form-control" required>
                    <option value="">-- Pilih Nama --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->name }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- <pre>{{ print_r($errors->all(), true) }}</pre> --}}
            <button type="submit" class="btn btn-primary w-100">Simpan</button>
        </form>
    </div>
@endsection
