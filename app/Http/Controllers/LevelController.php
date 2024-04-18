<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Factory;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class LevelController extends Controller
{
    public function index(): View|Application|Factory|ApplicationContract
    {
		$breadcrumb = (object) [
			'title' => 'Daftar Level',
			'list' => ['Home', 'Level']
		];

	    $page = (object) [
		    'title' => 'Daftar Level yang terdaftar dalam sistem'
	    ];

		$activeMenu = 'level';

		return view('level.index', [
			'breadcrumb' => $breadcrumb,
			'page' => $page,
			'activeMenu' => $activeMenu
		]);
    }

	public function list(): JsonResponse|\Exception
	{
		$levels = (new LevelModel)->select('level_id', 'level_nama');

		try {
			return DataTables::of($levels)
				->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
				->addColumn('aksi', function ($level) {
					$btn = '<a href="'.url('/level/' . $level->level_id).'" class="btn btn-info btn-sm">Detail</a> ';
					$btn .= '<a href="' . url('/level/' . $level->level_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
					$btn .= '<form class="d-inline-block" method="POST" action="'
						. url('/level/' . $level->level_id) . '">'
						. csrf_field()
						. method_field('DELETE')
						. '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
					return $btn;
				})
				->rawColumns(['aksi'])
				->editColumn('level_id', '')
				->make(true);
		} catch (\Exception $e) {
			return $e;
		}
	}

	public function create(): View|Application|Factory|ApplicationContract
	{
		$breadcrumb = (object) [
			'title' => 'Tambah Level',
			'list' => ['Home', 'Level', 'Tambah']
		];

		$page = (object) [
			'title' => 'Tambah level baru'
		];

		$activeMenu = 'level'; // set menu yang sedang aktif

		return view('level.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
	}

	public function store(Request $request): Application|Redirector|RedirectResponse|ApplicationContract
	{
		$request->validate([
			'kode' => 'required|string|min:3|unique:m_level,level_kode',
			'name' => 'required|string|max:50',
		]);

		LevelModel::create([
			'level_kode' => $request->kode,
			'level_nama' => $request->name,
		]);

		return redirect('/level')->with('success', 'Data level berhasil disimpan');
	}

	public function show(string $id): View|Application|Factory|ApplicationContract
	{
		$level = (new LevelModel)->find($id);

		$breadcrumb = (object) [
			'title' => 'Detail Level',
			'list' => ['Home', 'Level', 'Detail']
		];

		$page = (object) [
			'title' => 'Detail level'
		];

		$activeMenu = 'level';

		return view('level.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
	}

	public function edit(string $id)
	{
		$level = (new LevelModel)->find($id);

		$breadcrumb = (object) [
			'title' => 'Edit Level',
			'list' => ['Home', 'Level', 'Edit']
		];

		$page = (object) [
			'title' => 'Edit level'
		];

		$activeMenu = 'level'; // set menu yang sedang aktif
		return view('level.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
	}

	public function update(Request $request, string $id): Application|Redirector|RedirectResponse|ApplicationContract
	{

		$request->validate([
			'kode' => 'required|string|min:3|unique:m_level,level_kode,'.$id.',level_id',
			'name' => 'required|string|max:50',
		]);

		LevelModel::find($id)->update([
			'level_kode' => $request->kode,
			'level_nama' => $request->name,
		]);

		return redirect('/level')->with('success', 'Data level berhasil diubah');
	}

	public function destroy(string $id): Application|Redirector|RedirectResponse|ApplicationContract
	{
		$check = LevelModel::find($id);
		if(!$check) {
			return redirect('/level')->with('error', 'Data level tidak ditemukan');
		}

		try {
			LevelModel::destroy($id);

			return redirect('/level')->with('success', 'Data level berhasil dihapus');
		}catch (QueryException $e) {
			return redirect('/level')->with('error', 'Data level gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
		}
	}
}
