<?php

namespace App\Http\Controllers;

use App\Models\PenjualanModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TransaksiController extends Controller
{
	public function index() {
		$breadcrumb = (object) [
			'title' => 'Daftar Transaksi Penjualan',
			'list' => ['Home', 'Transaksi']
		];

		$page = (object) [
			'title' => 'Daftar Transaksi Penjualan yang terdaftar dalam sistem'
		];

		$activeMenu = 'penjualan';

		return view('penjualan.index', [
			'breadcrumb' => $breadcrumb,
			'page' => $page,
			'activeMenu' => $activeMenu
		]);
	}

	public function list(): JsonResponse
	{
		$transactions = (new PenjualanModel)->select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')->with('user');


		return DataTables::of($transactions)
			->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
			->addColumn('aksi', function ($transaction) {
				$btn = '<a href="'.url('/penjualan/' . $transaction->penjualan_id).'" class="btn btn-info btn-sm">Detail</a> ';
				$btn .= '<a href="'.url('/penjualan/' . $transaction->penjualan_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
				$btn .= '<form class="d-inline-block" method="POST" action="'
					. url('/penjualan/'.$transaction->penjualan_id).'">'
					. csrf_field()
					. method_field('DELETE')
					. '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
				return $btn;
			})
			->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
			->make(true);
	}
}
