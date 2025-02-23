@extends('layout.master')
@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">Daily Report</h1>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tambah Data Daily Report</h6>
            </div>
            <div class="card-body">

                <form method="GET" action="{{ route('layout.admin.rekap_admin') }}">
                    <label for="month">Bulan:</label>
                    <select name="month" id="month">
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endforeach
                    </select>

                    <label for="year">Tahun:</label>
                    <select name="year" id="year">
                        @foreach (range(date('Y') - 5, date('Y')) as $y)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit">Filter</button>
                </form>

                <a href="{{ route('rekap.export', ['month' => request('month'), 'year' => request('year')]) }}"
                    class="btn btn-success">Export Excel</a>


                <div class="table-responsive">
                    <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="text-center">
                                <th>Nama</th>
                                <th>Shalat Wjib</th>
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
                        <tfoot>
                            <tr>
                                <th>Total</th>
                                <th>{{ number_format($users->sum('report_sum_shalat_wajib')) }}</th>
                                <th>{{ number_format($users->sum('report_sum_qiyamul_lail')) }}</th>
                                <th>{{ number_format($users->sum('report_sum_tilawah')) }}</th>
                                <th>{{ number_format($users->sum('report_sum_duha')) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="{{ asset('admin/js/scriptkuring.js') }}"></script>
<script>
    var ctx = document.getElementById('rekapChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ["Shalat Wajib", "Qiyamul Lail", "Tilawah", "Duha"],
            datasets: [{
                label: 'Total Ibadah',
                data: [
                    {{ $users->sum('report_sum_shalat_wajib') }},
                    {{ $users->sum('report_sum_qiyamul_lail') }},
                    {{ $users->sum('report_sum_tilawah') }},
                    {{ $users->sum('report_sum_duha') }}
                ],
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e']
            }]
        }
    });
</script>
