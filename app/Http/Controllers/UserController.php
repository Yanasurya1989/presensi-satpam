<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $roles = Role::all();
        return view('layout.admin.index', compact('users', 'roles'));
    }

    public function insert()
    {
        $users = User::all();
        return view('layout.admin.insert', compact('users'));
    }

    public function store(Request $request)
    {
        // input
        $name = $request->name;
        $divisi = $request->divisi;
        $email = $request->email;
        $role_id = $request->role_id;
        $password = $request->password;

        if ($request->hasFile('foto')) {
            $path_loc = $request->file('foto');
            $url = $path_loc->move('storage/image', $path_loc->hashName());
        }

        // dd($request->all());

        // proses
        $simpan = User::create([
            'name' => $name,
            'divisi' => $divisi,
            'email' => $email,
            'role_id' => $role_id,
            'password' => $password,
            'foto' => $url->getPath() . '/' . $url->getFilename(),
        ]);

        // output
        return redirect('/users');
    }

    public function delete($id)
    {
        $id = decrypt($id);
        $deleteMarketer = User::where('id', $id)->delete();
        return redirect('/users')->with('success', 'Atos dihapus mang');
    }

    public function edit(User $user)
    {
        return view('layout/admin/edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            // 'id' => 'required',
            'name' => 'required',
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator->messages());
        }
        $data = [
            // 'id' => request()->id,
            'name' => request()->name,
            'email' => request()->email,
        ];
        $user = $user->update($data);
        if ($user) {
            return Redirect()->to('/users')->withSuccess('data berhasil diubah');
        } else {
            return back()->withErrors('gagal diubah datana');
        }
    }
}
