@extends('layout.master')
@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">{{ Auth::user()->email }}</h1>
        {{-- <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
            For more information about DataTables, please visit the <a target="_blank" href="https://datatables.net">official
                DataTables documentation</a>.</p> --}}

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Insert Mutaba'ah Milik {{ Auth::user()->email }}</h6>
            </div>
            <div class="card-body">

                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{ url('/report/store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
                        <label for="exampleInputEmail">Shalat Wajib</label>
                        <select name="shalat_wajib" id="category" class="form-control mb-4" required>
                            <option value="">-- Shalat wajid dalam sehari --</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                        <label for="exampleInputEmail">Qiyamul Lail</label>
                        <select name="qiyamul_lail" id="category" class="form-control mb-4" required>
                            <option value="">-- Jumlah raka'at --</option>
                            <option value="2">2</option>
                            <option value="4">4</option>
                            <option value="6">6</option>
                            <option value="8">8</option>
                            <option value="11">11</option>
                        </select>
                        <label for="exampleInputEmail">Tilawah</label>
                        <select name="tilawah" id="category" class="form-control mb-4" required>
                            <option value="">-- Jumlah halaman --</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                        <label for="exampleInputEmail">Shalat Duha</label>
                        <select name="duha" id="category" class="form-control mb-4" required>
                            <option value="">-- Jumlah raka'at --</option>
                            <option value="2">2</option>
                            <option value="4">4</option>
                            <option value="6">6</option>
                            <option value="8">8</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary">Cancel</button>
                        <button type="submit" class="btn btn-primary">Kirim Uang</button>
                    </div>
                </form>
            </div>
        </div>
    @endsection
