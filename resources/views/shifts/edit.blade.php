@extends('layout.master')
@section('content')
    <div class="container">
        <h3>Edit Shift Karyawan</h3>
        <form action="{{ route('user-shifts.update', $userShift->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="user_id">Karyawan</label>
                <select name="user_id" id="user_id" class="form-control" required>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ $user->id == $userShift->user_id ? 'selected' : '' }}>
                            {{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="shift_id">Shift</label>
                <select name="shift_id" id="shift_id" class="form-control" required>
                    @foreach ($shifts as $shift)
                        <option value="{{ $shift->id }}" {{ $shift->id == $userShift->shift_id ? 'selected' : '' }}>
                            {{ $shift->name }} ({{ $shift->start_time }} - {{ $shift->end_time }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="shift_date">Tanggal Shift</label>
                <input type="date" name="shift_date" value="{{ $userShift->shift_date }}" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Shift</button>
            <a href="{{ route('shift.assign') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection
