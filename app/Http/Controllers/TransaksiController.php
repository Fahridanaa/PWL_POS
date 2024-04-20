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
		$penjualan = (new PenjualanModel())
			->select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')
			->with('details', 'user')
			->get();

		$penjualan->transform(function ($item) {
			$total = 0;
			foreach ($item->details as $detail) {
				$total += $detail->harga * $detail->jumlah;
			}

			$item->total = $total;
			return $item;
		});


		return DataTables::of($penjualan)
			->addIndexColumn()
			->addColumn('aksi', function ($penjualan) {
				$btn = '<a href="'.url('/penjualan/' . $penjualan->penjualan_id).'" class="btn btn-info btn-sm">Detail</a> ';
				$btn .= '<a href="'.url('/penjualan/' . $penjualan->penjualan_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
				return $btn;
			})
			->addColumn('total', function ($penjualan) {
				return $penjualan->total;
			})
			->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
			->make(true);
	}

	public function create(): \Illuminate\Contracts\View\View|Application|\Illuminate\Contracts\View\Factory|ApplicationContract
	{
		$kode_penjualan = PenjualanModel::generateSaleCode();

		$breadcrumb = (object) [
			'title' => 'Tambah penjualan',
			'list' => ['Home', 'Penjualan', 'Tambah']
		];

		$page = (object) [
			'title' => 'Tambah penjualan baru'
		];

		$barang = BarangModel::all();
		$activeMenu = 'penjualan';

		return view('penjualan.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kode_penjualan' => $kode_penjualan, 'barang' => $barang, 'activeMenu' => $activeMenu]);
	}

	public function getHarga($id)
	{
		$barang = BarangModel::findOrFail($id); // Cari barang berdasarkan ID

		return response()->json([
			'harga_jual' => $barang->harga_jual // Kembalikan harga jual barang dalam respons JSON
		]);
	}

	public function store(Request $request): Application|Redirector|RedirectResponse|ApplicationContract
	{
		DB::beginTransaction();
		try {
			$datetime = (new DateTime())->setTimezone(new \DateTimeZone("Asia/Jakarta"));
			$penjualan = PenjualanModel::create([
				'user_id' => 1,
				'pembeli' => $request->pembeli,
				'penjualan_kode' => $request->penjualan_kode,
				'penjualan_tanggal' => $datetime,
			]);

			$barangIdArr = $request->barang_id;
			$hargaArr = $request->harga;
			$jumlahArr = $request->jumlah;

			for ($i = 0; $i < count($barangIdArr); $i++) {
				PenjualanDetailModel::create([
					'penjualan_id' => $penjualan->penjualan_id,
					'barang_id' => $barangIdArr[$i],
					'harga' => $hargaArr[$i],
					'jumlah' => $jumlahArr[$i],
				]);
			}
			DB::commit();
			return redirect('/penjualan')->with('success', 'Data penjualan berhasil disimpan');
		} catch (\Exception $e) {
			DB::rollback();
			return redirect('/penjualan')->with('error', 'Data penjualan gagal disimpan, Kesalahan: '.$e->getMessage());
		}
	}

	public function show(string $id): View|Application|Factory|ApplicationContract
	{
		$penjualan = (new PenjualanModel())->with('user')->find($id);
		$detail = (new PenjualanDetailModel())::with(['penjualan', 'barang'])->where('penjualan_id', $id)->get();

		$breadcrumb = (object) [
			'title' => 'Detail Penjualan',
			'list' => ['Home', 'Penjualan', 'Detail']
		];

		$page = (object) [
			'title' => 'Detail penjualan'
		];

		$activeMenu = 'penjualan';
		$count = 1;

		return view('penjualan.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'penjualan' => $penjualan, 'detail' => $detail, 'count' => $count, 'activeMenu' => $activeMenu]);
	}

	public function edit(string $id)
	{
		$penjualan = PenjualanModel::with(['details' => function ($query) {
			$query->withTrashed();
		}, 'details.barang'])->findOrFail($id);
		$barang = BarangModel::all();

		$breadcrumb = (object) [
			'title' => 'Edit Penjualan',
			'list' => ['Home', 'Penjualan', 'Edit']
		];

		$page = (object) [
			'title' => 'Edit penjualan'
		];

		$activeMenu = 'penjualan';
		return view('penjualan.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'penjualan'=>$penjualan, 'barang' => $barang, 'activeMenu' => $activeMenu]);
	}

	public function update(Request $request, string $id): ApplicationContract|Application|RedirectResponse|Redirector|\Exception
	{
		try {
			$request->validate([
				'penjualan_kode' => ['required', 'string', 'min:3', Rule::unique('t_penjualan')->ignore($id, 'penjualan_id')],
				'pembeli' => 'required|string',
				'detail_id' => 'required|array',
				'barang_id' => 'required|array',
				'harga' => 'required|array',
				'jumlah' => 'required|array',
				'deleted' => 'present|array',
				'restored' => 'present|array',
			]);

			DB::beginTransaction();

			$penjualan = PenjualanModel::findOrFail($id);

			$penjualan->update([
				'pembeli' => $request->pembeli,
				'penjualan_kode' => $request->penjualan_kode,
			]);

			$penjualan->penjualan_tanggal = (new DateTime())->setTimezone(new \DateTimeZone("Asia/Jakarta"));
			$penjualan->save();

			$detailIds = $request->detail_id;
			$barangIds = $request->barang_id;
			$hargas = $request->harga;
			$jumlahs = $request->jumlah;

			for($i = 0; $i < count($detailIds); $i++) {
				if ($detailIds[$i]) {
					$detail = PenjualanDetailModel::findOrFail($detailIds[$i]);
					$detail->update([
						'barang_id' => $barangIds[$i],
						'harga' => $hargas[$i],
						'jumlah' => $jumlahs[$i],
					]);
				} else {
					PenjualanDetailModel::create([
						'penjualan_id' => $penjualan->penjualan_id,
						'barang_id' => $barangIds[$i],
						'harga' => $hargas[$i],
						'jumlah' => $jumlahs[$i],
					]);
				}

				if (in_array($detailIds[$i], $request->deleted)) {
					$detail->delete();
				}

				if (in_array($detailIds[$i], $request->restored)) {
					$detail->restore();
				}
			}

			DB::commit();
			return redirect('/penjualan')->with('success', 'Data penjualan berhasil diupdate');
		} catch (\Exception $e) {
			DB::rollback();

			return redirect('/penjualan')->with('error', 'Data penjualan gagal diupdate');
		}
	}

	public function deleteDetail($penjualanId, $detailId)
	{
		$penjualan = PenjualanModel::findOrFail($penjualanId);
		$detail = $penjualan->details()->where('detail_id', $detailId)->firstOrFail();

		 $detail->delete();

		return redirect()->back()->with('success', 'Detail penjualan berhasil dihapus');
	}

	public function restoreDetail($penjualanId, $detailId)
	{
		$detail = PenjualanDetailModel::withTrashed()->where('penjualan_id', $penjualanId)->where('detail_id', $detailId)->firstOrFail();
		$detail->restore();
		return redirect()->back()->with('success', 'Detail penjualan berhasil dipulihkan');
	}
}
