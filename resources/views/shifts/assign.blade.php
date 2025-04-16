@extends('layout.master')
@section('content')
    <div class="container-fluid">

        <?php
        $role = Auth::user()->role->name;
        ?>
        @if ($role == 'Super Admin' || $role == 'Kabid 4')
            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800">Jadwal Shift</h1>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pembagian Shift Karyawan</h6>
                </div>
                <div class="card-body">

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif


                    <form action="{{ route('shift.assign') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Pilih Karyawan</label>
                            <select name="user_id" id="user_id" class="form-control" required>
                                <option value="">-- Pilih Karyawan --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="shift_id" class="form-label">Pilih Shift</label>
                            <select name="shift_id" id="shift_id" class="form-control" required>
                                <option value="">-- Pilih Shift --</option>
                                @foreach ($shifts as $shift)
                                    <option value="{{ $shift->id }}">{{ $shift->name }} ({{ $shift->start_time }} -
                                        {{ $shift->end_time }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="shift_date" class="form-label">Tanggal Shift</label>
                            <input type="date" name="shift_date" id="shift_date" class="form-control"
                                min="{{ date('Y-m-d') }}" required>
                        </div>


                        <button type="submit" class="btn btn-primary">Tetapkan Shift</button>
                    </form>

                    <hr>

                    <h3 class="mt-4">Daftar Shift Karyawan</h3>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Karyawan</th>
                                <th>Shift</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($userShifts as $userShift)
                                <tr>
                                    <td>{{ $userShift->user->name }}</td>
                                    <td>{{ $userShift->shift->name }} ({{ $userShift->shift->start_time }} -
                                        {{ $userShift->shift->end_time }})</td>
                                    <td>{{ $userShift->shift_date }}</td>
                                    {{-- <td>
                                        <form action="{{ route('user-shifts.destroy', $userShift->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus shift ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                        <form action="{{ route('user-shifts.destroy', $userShift->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus shift ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-success">Update</button>
                                        </form>
                                    </td> --}}
                                    <td>
                                        <form action="{{ route('user-shifts.destroy', $userShift->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus shift ini?');"
                                            style="display: inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>

                                        </form>
                                        {{-- <a href="{{ route('user-shifts.edit', $userShift->id) }}"
                                            class="btn btn-success">Update</a> --}}
                                        {{-- <form action="{{ route('user-shifts.update', $userShift->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success">Update</button>
                                        </form> --}}

                                        <a href="{{ route('user-shifts.edit', $userShift->id) }}"
                                            class="btn btn-success btn-sm">Edit</a>

                                    </td>

                                </tr>
                            @endforeach

                        </tbody>
                    </table>


                </div>
            </div>
        @else
            <p>Anda tidak bisa mengakses halaman ini</p>
        @endif
    </div>
@endsection

<script src="{{ asset('admin/js/scriptkuring.js') }}"></script>
