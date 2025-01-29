<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Mutaba'ah Yaumiyah</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('admin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('admin/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('layout.includes._sidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('layout.includes._header')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                {{-- @yield('content') --}}
                <div class="container-fluid">
                    <div class="content-wrapper">
                        <!-- Content Header (Page header) -->
                        <div class="content-header">
                            <div class="container-fluid">
                                <div class="row mb-2">
                                    <div class="col-sm-6">
                                        <h1 class="m-0 text-dark">Rekap Presensi Karyawan</h1>
                                    </div><!-- /.col -->
                                    <div class="col-sm-6">
                                        <ol class="breadcrumb float-sm-right">
                                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                                            <li class="breadcrumb-item active">Rekap Prersensi Karyawan</li>
                                        </ol>
                                    </div><!-- /.col -->
                                </div><!-- /.row -->
                            </div><!-- /.container-fluid -->
                        </div>

                        <!-- /.content-header -->

                        <!-- Main content -->
                        <div class="content">
                            <div class="row justify-content-center">
                                <div class="card card-info card-outline">
                                    <div class="card-header">Lihat Data</div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="label">Tanggal Awal</label>
                                            <input type="date" name="tglawal" id="tglawal" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <label for="label">Tanggal Akhir</label>
                                            <input type="date" name="tglakhir" id="tglakhir" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <a href=""
                                                onclick="this.href='/filter-data/'+ document.getElementById('tglawal').value +
                                        '/' + document.getElementById('tglakhir').value "
                                                class="btn btn-primary col-md-12">
                                                Lihat <i class="fas fa-print"></i>
                                            </a>
                                        </div>
                                        <div class="form-group">
                                            <table border="1">
                                                <tr>
                                                    <th>pegawai</th>
                                                    <th>Tanggal</th>
                                                    <th>Masuk</th>
                                                    <th>Keluar</th>
                                                    <th>Jumlah Jam Kerja</th>
                                                </tr>
                                                @foreach ($presensi as $item)
                                                    <tr>
                                                        <td>{{ $item->user->name }}</td>
                                                        <td>{{ $item->tgl }}</td>
                                                        <td>{{ $item->jammasuk }}</td>
                                                        <td>{{ $item->jamkeluar }}</td>
                                                        <td>{{ $item->jamkerja }}</td>
                                                    </tr>
                                                @endforeach
                                            </table>

                                        </div>
                                    </div><!-- /.container-fluid -->

                                </div>
                            </div>

                        </div>
                        <!-- /.content -->
                    </div>
                    <!-- /.content-wrapper -->

                    <!-- Control Sidebar -->
                    <aside class="control-sidebar control-sidebar-dark">
                        <!-- Control sidebar content goes here -->
                        <div class="p-3">
                            <h5>Title</h5>
                            <p>Sidebar content</p>
                        </div>
                    </aside>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            @include('layout.includes._footer')
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('admin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('admin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('admin/js/sb-admin-2.min.js') }}"></script>

    {{-- jam --}}
    <script src="{{ asset('admin/js/jam.js') }}"></script>

</body>

</html>
