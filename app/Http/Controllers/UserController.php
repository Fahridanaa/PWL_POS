<?php

namespace App\Http\Controllers;

use App\DataTables\UserDataTable;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(UserDataTable $dataTable) {
		return $dataTable->render('user.index');
    }

	public function tambah() {
		return view('user.tambah');
	}

	public function tambah_simpan(Request $request) {
		UserModel::create([
			'username' => $request->username,
			'name' => $request->name,
			'password' => Hash::make('$request->password'),
			'level_id' => $request->level_id
		]);

		return redirect('/user');
	}

	public function ubah($id)
	{
		$user = UserModel::find($id);
		return view('user.edit', ['data' => $user]);
	}

	public function ubah_simpan($id, Request $request) {
		$user = UserModel::find($id);

		$user->username = $request->username;
		$user->name = $request->name;
		$user->password = Hash::make('$request->password');
		$user->level_id = $request->level_id;

		$user->save();

		return redirect('/user');
	}

	public function hapus($id) {
		$user = UserModel::find($id);
		$user->delete();

		return redirect('/user');
	}
}
