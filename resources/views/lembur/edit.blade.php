@extends('layout.master')

@section('content')
    <div class="container">
        <h1>Edit Lembur</h1>
        <form action="{{ route('lembur.update', $lembur->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('lembur._form', ['lembur' => $lembur])
            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
@endsection
