<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use App\Models\LevelModel;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Factory;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class KategoriController extends Controller
{
	public function index(): View|Application|Factory|ApplicationContract
	{
		$breadcrumb = (object) [
			'title' => 'Daftar Kategori',
			'list' => ['Home', 'Kategori']
		];

		$page = (object) [
			'title' => 'Daftar Kategori yang terdaftar dalam sistem'
		];

		$activeMenu = 'kategori';

		return view('kategori.index', [
			'breadcrumb' => $breadcrumb,
			'page' => $page,
			'activeMenu' => $activeMenu
		]);
	}

	public function list(): JsonResponse
	{
		$categories = (new KategoriModel)->select('kategori_id', 'kategori_kode', 'kategori_nama');


		return DataTables::of($categories)
			->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
			->addColumn('aksi', function ($category) {
				$btn = '<a href="'.url('/kategori/' . $category->kategori_id).'" class="btn btn-info btn-sm">Detail</a> ';
				$btn .= '<a href="'.url('/kategori/' . $category->kategori_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
				$btn .= '<form class="d-inline-block" method="POST" action="'
					. url('/kategori/'.$category->kategori_id).'">'
					. csrf_field()
					. method_field('DELETE')
					. '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
				return $btn;
			})
			->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
			->make(true);
	}

	public function create(): \Illuminate\Contracts\View\View|Application|\Illuminate\Contracts\View\Factory|ApplicationContract
	{
		$breadcrumb = (object) [
			'title' => 'Tambah kategori',
			'list' => ['Home', 'Kategori', 'Tambah']
		];

		$page = (object) [
			'title' => 'Tambah kategori baru'
		];

		$level = KategoriModel::all(); // ambil data level untuk ditampilkan di form
		$activeMenu = 'kategori'; // set menu yang sedang aktif

		return view('kategori.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
	}

	public function store(Request $request): Application|Redirector|RedirectResponse|ApplicationContract
	{
		$request->validate([
			'kode' => 'required|string|min:3|unique:m_level,level_kode',
			'name' => 'required|string|max:50',
		]);

		LevelModel::create([
			'kategori_kode' => $request->kode,
			'kategori_nama' => $request->name,
		]);

		return redirect('/kategori')->with('success', 'Data level berhasil disimpan');
	}
}
