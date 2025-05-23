@extends('layout.master')

@section('content')
    <div class="container">
        <h3>Data Presensi</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Jam 2</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Photo</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($presences as $presence)
                    <tr>
                        <td>{{ $presence->id }}</td>
                        <td>{{ $presence->user->name ?? 'N/A' }}</td>
                        <td>{{ $presence->tanggal }}</td>
                        <td>{{ $presence->jam }}</td>
                        <td>{{ $presence->jam_2 ?? '-' }}</td>
                        <td>{{ $presence->created_at }}</td>
                        <td>{{ $presence->updated_at }}</td>
                        <td>
                            @if ($presence->photo)
                                <img src="{{ $presence->photo }}" alt="photo" width="60">
                            @else
                                Tidak ada
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.presensi.edit', $presence->id) }}"
                                class="btn btn-sm btn-warning">Ubah</a>
                            <a href="#" onclick="copyToClipboard('{{ $presence->photo }}')"
                                class="btn btn-sm btn-secondary">Salin</a>
                            <form action="{{ route('admin.presensi.destroy', $presence->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Yakin hapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Tersalin!');
            }, function(err) {
                alert('Gagal menyalin!');
            });
        }
    </script>
@endsection
