<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\PenjualanDetailModel;
use App\Models\PenjualanModel;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class TransaksiController extends Controller
{
	public function index() {
		$breadcrumb = (object) [
			'title' => 'Daftar Transaksi Penjualan',
			'list' => ['Home', 'Penjualan']
		];

		$page = (object) [
			'title' => 'Daftar Penjualan yang terdaftar dalam sistem'
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
		$details = (new PenjualanDetailModel)->select('detail_id', 'penjualan_id', 'barang_id', 'harga', 'jumlah')->with(['penjualan', 'penjualan.user', 'barang']);


		return DataTables::of($details)
			->addIndexColumn()
			->addColumn('aksi', function ($detail) {
				$btn = '<a href="'.url('/penjualan/' . $detail->detail_id).'" class="btn btn-info btn-sm">Detail</a> ';
				$btn .= '<a href="'.url('/penjualan/' . $detail->detail_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
				$btn .= '<form class="d-inline-block" method="POST" action="'
					. url('/penjualan/'.$detail->detail_id).'">'
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
			'title' => 'Tambah penjualan',
			'list' => ['Home', 'Penjualan', 'Tambah']
		];

		$page = (object) [
			'title' => 'Tambah penjualan baru'
		];

		$barang = BarangModel::all();
		$activeMenu = 'penjualan';

		return view('penjualan.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'activeMenu' => $activeMenu]);
	}

	public function store(Request $request): Application|Redirector|RedirectResponse|ApplicationContract
	{
		$request->validate([
			'penjualan_kode' => 'required|string|min:3|unique:t_penjualan,penjualan_kode',
			'pembeli' => 'required|string',
			'barang_id' => 'required|integer',
			'harga' => 'required|integer|between:1,999999999',
			'jumlah' => 'required|integer',
		]);

		$datetime = (new DateTime())->setTimezone(new \DateTimeZone("Asia/Jakarta"));

		DB::beginTransaction();

		try {
			$penjualan = PenjualanModel::create([
				'user_id' => 1,
				'pembeli' => $request->pembeli,
				'penjualan_kode' => $request->penjualan_kode,
				'penjualan_tanggal' => $datetime,
			]);

			PenjualanDetailModel::create([
				'penjualan_id' => $penjualan->penjualan_id,
				'barang_id' => $request->barang_id,
				'harga' => $request->harga,
				'jumlah' => $request->jumlah
			]);

			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
		}

		return redirect('/penjualan')->with('success', 'Data penjualan berhasil disimpan');
	}

	public function show(string $id): View|Application|Factory|ApplicationContract
	{
		$detail = PenjualanDetailModel::with(['penjualan', 'penjualan.user', 'barang'])->find($id);

		$breadcrumb = (object) [
			'title' => 'Detail Penjualan',
			'list' => ['Home', 'Penjualan', 'Detail']
		];

		$page = (object) [
			'title' => 'Detail penjualan'
		];

		$activeMenu = 'penjualan';

		return view('penjualan.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'detail' => $detail, 'activeMenu' => $activeMenu]);
	}

	public function edit(string $id)
	{
		$detail = PenjualanDetailModel::find($id);
		$penjualan = PenjualanModel::find($detail->penjualan_id);
		$barang = BarangModel::all();

		$breadcrumb = (object) [
			'title' => 'Edit Penjualan',
			'list' => ['Home', 'Penjualan', 'Edit']
		];

		$page = (object) [
			'title' => 'Edit penjualan'
		];

		$activeMenu = 'penjualan';
		return view('penjualan.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'detail' => $detail, 'penjualan'=>$penjualan, 'barang' => $barang, 'activeMenu' => $activeMenu]);
	}

	public function update(Request $request, string $id): ApplicationContract|Application|RedirectResponse|Redirector|\Exception
	{
		try {
		$detail_penjualan = PenjualanDetailModel::findOrFail($id);
		$request->validate([
			'penjualan_kode' => ['required', 'string', 'min:3', Rule::unique('t_penjualan')->ignore($detail_penjualan->penjualan_id, 'penjualan_id')],
			'pembeli' => 'required|string',
			'barang_id' => 'required|integer',
			'harga' => 'required|integer|between:1,999999999',
			'jumlah' => 'required|integer',
		]);

		DB::beginTransaction();

			$detail_penjualan->update($request->only(['barang_id', 'harga', 'jumlah']));

			$penjualan = PenjualanModel::findOrFail($detail_penjualan->penjualan_id);
			$penjualan->update($request->only([
				'pembeli',
				'penjualan_kode'
			]));

			$penjualan->penjualan_tanggal = (new DateTime())->setTimezone(new \DateTimeZone("Asia/Jakarta"));
			$penjualan->save();

			DB::commit();

			return redirect('/penjualan')->with('success', 'Data penjualan berhasil diupdate');
		}catch (\Exception $e) {
			DB::rollback();

			return redirect('/penjualan')->with('error', 'Data penjualan gagal diupdate');
		}
	}

	public function destroy(string $id): Application|Redirector|RedirectResponse|ApplicationContract
	{
		$detail_penjualan = PenjualanDetailModel::findOrFail($id);
		$penjualan_id = $detail_penjualan->penjualan_id;

		DB::beginTransaction();

		try {
			PenjualanDetailModel::destroy($id);
			PenjualanModel::destroy($penjualan_id);

			DB::commit();
			return redirect('/penjualan')->with('success', 'Data penjualan berhasil dihapus');
		}catch (\Exception $e) {
			DB::rollback();
			return redirect('/penjualan')->with('error', 'Data penjualan gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
		}
	}
}
