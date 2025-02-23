<table>
    <thead>
        <tr>
            <th>Nama</th>
            <th>Shalat Wajib</th>
            <th>Qiyamul Lail</th>
            <th>Tilawah</th>
            <th>Duha</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $data)
            <tr>
                <td>{{ $data->name }}</td>
                <td>{{ number_format($data->report_sum_shalat_wajib) }}</td>
                <td>{{ number_format($data->report_sum_qiyamul_lail) }}</td>
                <td>{{ number_format($data->report_sum_tilawah) }}</td>
                <td>{{ number_format($data->report_sum_duha) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
