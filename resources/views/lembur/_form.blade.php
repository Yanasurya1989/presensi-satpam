{{-- <div class="mb-3">
    <label for="user_id" class="form-label">User</label>
    <select name="user_id" id="user_id" class="form-control" required>
        <option value="">-- Pilih User --</option>
        @foreach ($users as $user)
            <option value="{{ $user->id }}"
                {{ old('user_id', $lembur->user_id ?? '') == $user->id ? 'selected' : '' }}>
                {{ $user->name }}
            </option>
        @endforeach
    </select>
</div> --}}

<pre>{{ json_encode($lembur, JSON_PRETTY_PRINT) }}</pre>


<div class="mb-3">
    <label for="user_id" class="form-label">User</label>
    <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
</div>

{{-- <div class="mb-3">
    <label for="user_id" class="form-label">User</label>
    <select name="user_id" id="user_id" class="form-control" readonly disabled>
        <option value="{{ auth()->id() }}">{{ auth()->user()->name }}</option>
    </select>
    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
</div> --}}


<div class="mb-3">
    <label for="tanggal" class="form-label">Tanggal</label>
    <input type="date" class="form-control" name="tanggal" value="{{ old('tanggal', $lembur->tanggal ?? '') }}"
        required>
</div>

@foreach (['satu', 'dua', 'tiga'] as $i)
    <div class="mb-3">
        <label for="mulai_lembur_{{ $i }}" class="form-label">Mulai Lembur {{ ucfirst($i) }}</label>
        <input type="time" class="form-control" name="mulai_lembur_{{ $i }}" {{-- value="{{ old("mulai_lembur_$i", $lembur["mulai_lembur_$i"] ?? '') }}" --}}
            value="{{ old("mulai_lembur_$i", $lembur?->{'mulai_lembur_' . $i} ?? '') }}">

    </div>
    <div class="mb-3">
        <label for="akhir_lembur_{{ $i }}" class="form-label">Akhir Lembur {{ ucfirst($i) }}</label>
        <input type="time" class="form-control" name="akhir_lembur_{{ $i }}" {{-- value="{{ old("akhir_lembur_$i", $lembur["akhir_lembur_$i"] ?? '') }}" --}}
            value="{{ old("akhir_lembur_$i", $lembur?->{'akhir_lembur_' . $i} ?? '') }}">

    </div>
@endforeach
