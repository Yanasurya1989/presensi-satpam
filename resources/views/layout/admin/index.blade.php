@extends('layout.master')
@section('content')
    <div class="container-fluid">

        <?php
        $role = Auth::user()->role->name;
        ?>
        @if ($role == 'Super Admin' || $role == 'Admin')
            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800">Users List</h1>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tambah Data User</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col">
                            <button type="button" class="btn btn-primary mb-3" data-toggle="modal"
                                data-target="#addUnitModal">
                                Add Users
                            </button>
                            <a href="{{ url('/export-users') }}" class="btn btn-success mb-3">
                                Export Excel
                            </a>
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

                                                <div class="form-group">
                                                    <label for="exampleInputEmail1" class="form-label">Unit</label>
                                                    <input type="text" class="form-control" id="divisi"
                                                        name="divisi">
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

                                                <div class="mb-3">
                                                    <label for="exampleInputEmail1" class="form-label">Role</label>
                                                    <select name="role_id" id="category" class="form-control mb-4" required
                                                        name="role_id">
                                                        <option value="">-- Pilih role --</option>
                                                        @foreach ($roles as $data)
                                                            <option value="{{ $data->id }}">{{ $data->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

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
                                    <th>Nama</th>
                                    <th>Unit</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Gambar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $data)
                                    <tr>
                                        <td>{{ $data['name'] }}</td>
                                        <td>{{ $data['divisi'] }}</td>
                                        <td>{{ $data['role_id'] }}</td>
                                        <td>{{ $data['email'] }}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#imageModal{{ $data['id'] }}">
                                                Lihat Foto
                                            </button>

                                            {{-- Modal --}}
                                            <div class="modal fade" tabindex="-1" id="imageModal{{ $data['id'] }}"
                                                aria-labelledby="addSliderModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">View Foto</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <img src="{{ asset($data['foto']) }}" class="img-fluid"
                                                                alt="">
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ url('/users/delete', encrypt($data->id)) }}"
                                                class="btn btn-danger"
                                                onclick="return confirm('Yakin akan dihapus?')">Hapus</a>

                                            <a href="/user/edit/{{ $data->id }}" class="btn btn-warning">Update</a>

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
