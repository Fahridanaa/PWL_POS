<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    public function index(): View|Application|Factory|ApplicationContract
    {
		$breadcrumb = (object) [
			'title' => 'Daftar User',
			'list' => ['Home', 'User']
		];

		$page = (object) [
			'title' => 'Daftar user yang terdaftar dalam sistem'
		];

		$activeMenu = 'user';

		$level = LevelModel::all();

		return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

	// Ambil data user dalam bentuk json untuk datatables
	public function list(Request $request): JsonResponse
	{
		$users = (new UserModel)->select('user_id', 'username', 'name', 'level_id')->with('level');

		if ($request->level_id) {
			$users->where('level_id', $request->level_id);
		}


		return DataTables::of($users)
			->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
			->addColumn('aksi', function ($user) {
				$btn = '<a href="'.url('/user/' . $user->user_id).'" class="btn btn-info btn-sm">Detail</a> ';
				$btn .= '<a href="'.url('/user/' . $user->user_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
				$btn .= '<form class="d-inline-block" method="POST" action="'
					. url('/user/'.$user->user_id).'">'
					. csrf_field()
					. method_field('DELETE')
					. '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
				return $btn;
			})
			->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
			->make(true);
	}

	public function create(): View|Application|Factory|ApplicationContract
	{
		$breadcrumb = (object) [
			'title' => 'Tambah User',
			'list' => ['Home', 'User', 'Tambah']
		];

		$page = (object) [
			'title' => 'Tambah user baru'
		];

		$level = LevelModel::all(); // ambil data level untuk ditampilkan di form
		$activeMenu = 'user'; // set menu yang sedang aktif

		return view('user.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
	}

	public function store(Request $request): Application|Redirector|RedirectResponse|ApplicationContract
	{
		$request->validate([
			// username harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_user kolom username
			'username' => 'required|string|min:3|unique:m_user,username',
			'name' => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter
			'password' => 'required|min:5', // password harus diisi dan minimal 5 karakter
			'level_id' => 'required|integer' // level_id harus diisi dan berupa angka
		]);

		UserModel::create([
			'username' => $request->username,
			'name' => $request->name,
			'password' => bcrypt($request->password), //password dienkripsi sebelum disimpan
			'level_id' => $request->level_id
		]);

		return redirect('/user')->with('success', 'Data user berhasil disimpan');
	}

	public function show(string $id): View|Application|Factory|ApplicationContract
	{
		$user = UserModel::with('level')->find($id);

		$breadcrumb = (object) [
			'title' => 'Detail User',
			'list' => ['Home', 'User', 'Detail']
		];

		$page = (object) [
			'title' => 'Detail user'
		];

		$activeMenu = 'user';

		return view('user.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu]);
	}

	public function edit(string $id)
	{
		$user = UserModel::find($id);
		$level = LevelModel::all();

		$breadcrumb = (object) [
			'title' => 'Edit User',
			'list' => ['Home', 'User', 'Edit']
		];

		$page = (object) [
			'title' => 'Edit user'
		];

		$activeMenu = 'user'; // set menu yang sedang aktif
		return view('user.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'level' => $level, 'activeMenu' => $activeMenu]);
	}

	public function update(Request $request, string $id)
	{
		$request->validate([
			'username' => 'required|string|min:3|unique:m_user,username,'.$id.',user_id',
			'name' => 'required|string|max:100',
			'password' => 'nullable|min:5',
			'level_id' => 'required|integer'
		]);

		UserModel::find($id)->update([
			'username' => $request->username,
			'name' => $request->name,
			'password' => $request->password ? bcrypt($request->password) : UserModel::find($id)->password,
			'level_id' => $request->level_id
		]);

		return redirect('/user')->with('success', 'Data user berhasil diubah');
	}

	public function destroy(string $id): Application|Redirector|RedirectResponse|ApplicationContract
	{
		$check = UserModel::find($id);
		if(!$check) {
			return redirect('/user')->with('error', 'Data user tidak ditemukan');
		}

		try {
			UserModel::destroy($id);

			return redirect('/user')->with('success', 'Data user berhasil dihapus');
		}catch (QueryException $e) {
			return redirect('/user')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
		}
	}
}
