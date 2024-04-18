<?php

namespace App\Http\Controllers;

use App\Models\StockModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
		$stocks = (new StockModel)->select('stok_id', 'barang_id', 'stok_tanggal', 'stok_jumlah')->with('barang');

		return DataTables::of($stocks)
			->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
			->addColumn('aksi', function ($stock) {
				$btn = '<a href="'.url('/stok/' . $stock->stok_id).'" class="btn btn-info btn-sm">Detail</a> ';
				$btn .= '<a href="'.url('/stok/' . $stock->stok_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
				$btn .= '<form class="d-inline-block" method="POST" action="'
					. url('/stok/'.$stock->stok_id).'">'
					. csrf_field()
					. method_field('DELETE')
					. '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
				return $btn;
			})
			->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
			->make(true);
	}
}
