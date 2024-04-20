<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\KategoriModel;
use App\Models\StockModel;
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
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

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

		$kategori = KategoriModel::all();

	    return view('barang.index', [
		    'breadcrumb' => $breadcrumb,
		    'page' => $page,
		    'activeMenu' => $activeMenu,
		    'kategori' => $kategori
	    ]);
    }

	public function list(Request $request): JsonResponse
	{
		$items = (new BarangModel)->select('barang_id', 'kategori_id', 'barang_kode', 'barang_name', 'harga_beli', 'harga_jual')->with('kategori');

		if ($request->kategori_id) {
			$items->where('kategori_id', $request->kategori_id);
		}

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

	public function create(): \Illuminate\Contracts\View\View|Application|\Illuminate\Contracts\View\Factory|ApplicationContract
	{
		$breadcrumb = (object) [
			'title' => 'Tambah barang',
			'list' => ['Home', 'Barang', 'Tambah']
		];

		$page = (object) [
			'title' => 'Tambah barang baru'
		];

		$kategori = KategoriModel::all(); // ambil data level untuk ditampilkan di form
		$activeMenu = 'barang'; // set menu yang sedang aktif

		return view('barang.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
	}

	public function store(Request $request): Application|Redirector|RedirectResponse|ApplicationContract
	{
		$datetime = (new DateTime())->setTimezone(new \DateTimeZone("Asia/Jakarta"));
		try{
			$request->validate([
				'barang_kode' => 'required|string|min:3|unique:m_barang,barang_kode',
				'barang_name' => 'required|string|max:50',
				'harga_beli' => 'required|integer',
				'harga_jual' => 'required|integer',
				'kategori_id' => 'required|integer'
			]);

			$barang = BarangModel::create([
				'barang_kode' => $request->barang_kode,
				'barang_name' => $request->barang_name,
				'harga_beli' => $request->harga_beli,
				'harga_jual' => $request->harga_jual,
				'kategori_id' => $request->kategori_id
			]);

			StockModel::create([
				'barang_id' => $barang->barang_id,
				'user_id' => 1,
				'stok_tanggal' => $datetime,
				'stok_jumlah' => 0
			]);

			return redirect('/barang')->with('success', 'Data barang berhasil disimpan');
		}catch (\Exception $e)
		{
			return redirect('/barang')->with('error', 'Data penjualan gagal disimpan, Kesalahan: '.$e->getMessage());
		}

	}

	public function show(string $id): View|Application|Factory|ApplicationContract
	{
		$barang = BarangModel::with('kategori')->find($id);

		$breadcrumb = (object) [
			'title' => 'Detail Barang',
			'list' => ['Home', 'Barang', 'Detail']
		];

		$page = (object) [
			'title' => 'Detail barang'
		];

		$activeMenu = 'barang';

		return view('barang.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'activeMenu' => $activeMenu]);
	}

	public function edit(string $id)
	{
		$barang = BarangModel::find($id);
		$kategori = KategoriModel::all();

		$breadcrumb = (object) [
			'title' => 'Edit Barang',
			'list' => ['Home', 'Barang', 'Edit']
		];

		$page = (object) [
			'title' => 'Edit barang'
		];

		$activeMenu = 'barang'; // set menu yang sedang aktif
		return view('barang.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
	}

	public function update(Request $request, string $id): ApplicationContract|Application|RedirectResponse|Redirector|\Exception
	{
		$request->validate([
			'barang_kode' => 'required|string|min:3|unique:m_barang,barang_kode,'.$id.',barang_id',
			'barang_name' => 'required|string|max:50',
			'harga_beli' => 'required|integer',
			'harga_jual' => 'required|integer',
			'kategori_id' => 'required|integer'
		]);

		BarangModel::find($id)->update([
			'barang_kode' => $request->barang_kode,
			'barang_name' => $request->barang_name,
			'harga_beli' => $request->harga_beli,
			'harga_jual' => $request->harga_jual,
			'kategori_id' => $request->kategori_id
		]);

		return redirect('/barang')->with('success', 'Data barang berhasil diubah');
	}

	public function destroy(string $id): Application|Redirector|RedirectResponse|ApplicationContract
	{
		$check = BarangModel::find($id);
		if(!$check) {
			return redirect('/barang')->with('error', 'Data barang tidak ditemukan');
		}

		try {
			BarangModel::destroy($id);

			return redirect('/barang')->with('success', 'Data barang berhasil dihapus');
		}catch (QueryException $e) {
			return redirect('/barang')->with('error', 'Data barang gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
		}
	}
}
