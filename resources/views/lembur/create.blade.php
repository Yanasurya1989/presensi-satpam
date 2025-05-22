@extends('layout.master')

@section('content')
    <div class="container">
        <h1>Tambah Lembur</h1>
        <form action="{{ route('lembur.store') }}" method="POST">
            @csrf
            @include('lembur._form')
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection
