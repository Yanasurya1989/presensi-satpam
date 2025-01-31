@extends('layout.master')
@section('content')
    <div class="container-fluid">

        <?php
        $role = Auth::user()->role->name;
        ?>
        @if ($role == 'Super Admin' || $role == 'Admin')
            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800">Shift List</h1>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tambah Data Shift</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col">
                            <button type="button" class="btn btn-primary mb-3" data-toggle="modal"
                                data-target="#addUnitModal">
                                Add Shift
                            </button>
                            {{-- modal --}}
                            <div class="modal fade" tabindex="-1" id="addUnitModal" aria-labelledby="addUnitModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Add User</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ url('users/store') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1" class="form-label">Name</label>
                                                    <input type="text" class="form-control" id="judul"
                                                        name="name">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="exampleInputEmail1" class="form-label">Email</label>
                                                    <input type="text" class="form-control" id="deskripsi"
                                                        name="email">
                                                </div>
                                                {{-- <div class="mb-3">
                                                    <label for="exampleInputEmail1" class="form-label">Role</label>
                                                    <input type="text" class="form-control" id="deskripsi"
                                                        name="role_id">
                                                </div> --}}
                                                <div class="mb-3">
                                                    <label for="exampleInputEmail1" class="form-label">Password</label>
                                                    <input type="text" class="form-control" id="deskripsi"
                                                        name="password">
                                                </div>

                                                {{-- <div class="mb-3">
                                                    <label for="exampleInputEmail1" class="form-label">Role</label>
                                                    <select name="role_id" id="category" class="form-control mb-4" required
                                                        name="role_id">
                                                        <option value="">-- Pilih role --</option>
                                                        @foreach ($roles as $data)
                                                            <option value="{{ $data->id }}">{{ $data->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div> --}}

                                                <div class="mb-3">
                                                    <label for="exampleInputEmail1" class="form-label">Foto</label>
                                                    <input type="file" class="form-control" id="gambar"
                                                        name="foto">
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Mulai</th>
                                    <th class="text-center">Akhir</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($shifts as $data)
                                    <tr>
                                        <td class="text-center">{{ $data->name }}</td>
                                        <td class="text-center">{{ $data->start_time }}</td>
                                        <td class="text-center">{{ $data->end_time }}</td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-danger"
                                                onclick="return confirm('Yakin akan dihapus?')">Hapus</a>

                                            <a href="#" class="btn btn-warning">Update</a>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <p>Anda tidak bisa mengakses halaman ini</p>
        @endif
    </div>
@endsection

<script src="{{ asset('admin/js/scriptkuring.js') }}"></script>
