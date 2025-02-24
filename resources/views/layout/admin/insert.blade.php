@extends('layout.master')
@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">{{ Auth::user()->name }}</h1>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Insert Mutaba'ah Milik {{ Auth::user()->name }}</h6>
            </div>
            <div class="card-body">

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @php
                    $todayReport = \App\Models\Report::where('id_user', Auth::id())
                        ->where('tanggal', \Carbon\Carbon::now()->format('Y-m-d'))
                        ->first();
                @endphp

                @if (!$todayReport)
                    <form action="{{ url('/report/store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
                        <div class="form-group">
                            <label>Shalat Wajib</label>
                            <select name="shalat_wajib" class="form-control mb-4" required>
                                <option value="">-- Shalat wajib dalam sehari --</option>
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>

                            <label>Qiyamul Lail</label>
                            <select name="qiyamul_lail" class="form-control mb-4" required>
                                <option value="">-- Ya/Tidak --</option>
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>

                            <label>Tilawah</label>
                            <select name="tilawah" class="form-control mb-4" required>
                                <option value="">-- Jumlah halaman --</option>
                                @for ($i = 0; $i <= 100; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>

                            <label>Shalat Duha</label>
                            <select name="duha" class="form-control mb-4" required>
                                <option value="">-- Ya/Tidak --</option>
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </form>
                @else
                    <div class="alert alert-warning">Anda sudah mengisi data hari ini.</div>
                @endif
            </div>
        </div>
    </div>
@endsection
