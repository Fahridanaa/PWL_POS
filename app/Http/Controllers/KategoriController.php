<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Database\QueryException;
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
		$categories = (new KategoriModel)->select('kategori_id', 'kategori_nama');


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

		$activeMenu = 'kategori'; // set menu yang sedang aktif

		return view('kategori.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
	}

	public function store(Request $request): Application|Redirector|RedirectResponse|ApplicationContract
	{
		$request->validate([
			'kode' => 'required|string|min:3|unique:m_level,level_kode',
			'name' => 'required|string|max:50',
		]);

		KategoriModel::create([
			'kategori_kode' => $request->kode,
			'kategori_nama' => $request->name,
		]);

		return redirect('/kategori')->with('success', 'Data kategori berhasil disimpan');
	}

	public function show(string $id): View|Application|Factory|ApplicationContract
	{
		$kategori = (new KategoriModel)->find($id);

		$breadcrumb = (object) [
			'title' => 'Detail Kategori',
			'list' => ['Home', 'Kategori', 'Detail']
		];

		$page = (object) [
			'title' => 'Detail kategori'
		];

		$activeMenu = 'kategori';

		return view('kategori.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
	}

	public function edit(string $id)
	{
		$kategori = (new KategoriModel)->find($id);

		$breadcrumb = (object) [
			'title' => 'Edit Kategori',
			'list' => ['Home', 'Kategori', 'Edit']
		];

		$page = (object) [
			'title' => 'Edit kategori'
		];

		$activeMenu = 'kategori'; // set menu yang sedang aktif
		return view('kategori.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
	}

	public function update(Request $request, string $id): Application|Redirector|RedirectResponse|ApplicationContract
	{

		$request->validate([
			'kode' => 'required|string|min:3|unique:m_level,level_kode',
			'name' => 'required|string|max:50',
		]);

		KategoriModel::find($id)->update([
			'kategori_kode' => $request->kode,
			'kategori_nama' => $request->name,
		]);

		return redirect('/kategori')->with('success', 'Data kategori berhasil diubah');
	}

	public function destroy(string $id): Application|Redirector|RedirectResponse|ApplicationContract
	{
		$check = KategoriModel::find($id);
		if(!$check) {
			return redirect('/kategori')->with('error', 'Data kategori tidak ditemukan');
		}

		try {
			KategoriModel::destroy($id);
			return redirect('/kategori')->with('success', 'Data kategori berhasil dihapus');
		}catch (QueryException $e) {
			return redirect('/kategori')->with('error', 'Data kategori gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
		}
	}
}
