<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\StockModel;
use App\Models\UserModel;
use DateTime;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Yajra\DataTables\DataTables;

class StockController extends Controller
{
	public function index() {
		$breadcrumb = (object) [
			'title' => 'Daftar Stok Barang',
			'list' => ['Home', 'Stok']
		];

		$page = (object) [
			'title' => 'Daftar stok barang yang terdaftar dalam sistem'
		];

		$activeMenu = 'stok';

		return view('stok.index', [
			'breadcrumb' => $breadcrumb,
			'page' => $page,
			'activeMenu' => $activeMenu
		]);
	}

	public function list(): JsonResponse
	{
		$stocks = (new StockModel)->select('stok_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah')->with('barang')->with('user');

		return DataTables::of($stocks)
			->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
			->addColumn('aksi', function ($stock) {
				$btn = '<a href="'.url('/stok/' . $stock->stok_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
				return $btn;
			})
			->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
			->make(true);
	}

	public function create(): \Illuminate\Contracts\View\View|Application|\Illuminate\Contracts\View\Factory|ApplicationContract
	{
		$breadcrumb = (object) [
			'title' => 'Tambah stok',
			'list' => ['Home', 'Stok', 'Tambah']
		];

		$page = (object) [
			'title' => 'Tambah stok baru'
		];

		$user = UserModel::all();
		$barang = BarangModel::all();
		$activeMenu = 'stok'; // set menu yang sedang aktif

		return view('stok.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'barang' => $barang, 'activeMenu' => $activeMenu]);
	}

	public function show(string $id): View|Application|Factory|ApplicationContract
	{
		$stok = StockModel::with('barang')->with('user')->find($id);

		$breadcrumb = (object) [
			'title' => 'Detail Stok',
			'list' => ['Home', 'Stok', 'Detail']
		];

		$page = (object) [
			'title' => 'Detail stok'
		];

		$activeMenu = 'stok';

		return view('stok.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'stok' => $stok, 'activeMenu' => $activeMenu]);
	}

	public function edit(string $id)
	{
		$stok = StockModel::find($id);
		$barang = BarangModel::all();

		$breadcrumb = (object) [
			'title' => 'Edit Stok',
			'list' => ['Home', 'Stok', 'Edit']
		];

		$page = (object) [
			'title' => 'Edit stok'
		];

		$activeMenu = 'stok';
		return view('stok.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'stok' => $stok, 'activeMenu' => $activeMenu]);
	}

	public function update(Request $request, string $id): ApplicationContract|Application|RedirectResponse|Redirector|\Exception
	{
		$request->validate([
			'barang_id' => 'required|integer',
			'stok_jumlah' => 'required|integer'
		]);

		$datetime = (new DateTime())->setTimezone(new \DateTimeZone("Asia/Jakarta"));

		StockModel::find($id)->update([
			'barang_id' => $request->barang_id,
			'stok_jumlah' => $request->stok_jumlah,
			'user_id' => 1,
			'stok_tanggal' => $datetime
		]);

		return redirect('/stok')->with('success', 'Data stok berhasil diubah');
	}
}
