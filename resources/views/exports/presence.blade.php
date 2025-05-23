<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Jam</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($presences as $index => $presence)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($presence->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $presence->jam }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
