<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BarangController extends Controller
{
    public function index() {
	    $breadcrumb = (object) [
		    'title' => 'Daftar Barang',
		    'list' => ['Home', 'Barang']
	    ];

	    $page = (object) [
		    'title' => 'Daftar Barang yang terdaftar dalam sistem'
	    ];

	    $activeMenu = 'barang';

	    return view('barang.index', [
		    'breadcrumb' => $breadcrumb,
		    'page' => $page,
		    'activeMenu' => $activeMenu
	    ]);
    }

	public function list(): JsonResponse
	{
		$items = (new BarangModel)->select('barang_id', 'kategori_id', 'barang_kode', 'barang_name', 'harga_beli', 'harga_jual')->with('kategori');


		return DataTables::of($items)
			->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
			->addColumn('aksi', function ($item) {
				$btn = '<a href="'.url('/barang/' . $item->barang_id).'" class="btn btn-info btn-sm">Detail</a> ';
				$btn .= '<a href="'.url('/barang/' . $item->barang_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
				$btn .= '<form class="d-inline-block" method="POST" action="'
					. url('/barang/'.$item->barang_id).'">'
					. csrf_field()
					. method_field('DELETE')
					. '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
				return $btn;
			})
			->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
			->make(true);
	}
}
