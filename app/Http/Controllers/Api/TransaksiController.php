<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PenjualanDetailModel;
use App\Models\PenjualanModel;
use App\Models\StockModel;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(): Collection
	{
		return PenjualanModel::all();
	}

	/**
	 * Store a newly created resource in storage.
	 */


	public function store(Request $request): JsonResponse
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
				$stokBarang = StockModel::where('barang_id', $barangIdArr[$i])->first()->stok_jumlah;

				if ($jumlahArr[$i] > $stokBarang) {
					DB::rollback();
					return response()->json([
						'success' => false,
						'errors' => 'Stok barang tidak mencukupi',
					], 422);
				}

				PenjualanDetailModel::create([
					'penjualan_id' => $penjualan->penjualan_id,
					'barang_id' => $barangIdArr[$i],
					'harga' => $hargaArr[$i],
					'jumlah' => $jumlahArr[$i],
				]);

				$stok = StockModel::where('barang_id', $barangIdArr[$i])->first();
				$stok->stok_jumlah = $stokBarang - $jumlahArr[$i];
				$stok->save();
			}

			DB::commit();
			return response()->json([
				'success' => true,
				'message' => 'Transaksi penjualan berhasil disimpan'

			]);
		} catch (\Exception $e) {
			DB::rollback();
			return response()->json([
				'success' => false,
				'errors' => $e->getMessage(),
			], 422);
		}
	}

	/**
	 * Display the specified resource.
	 */
	public function show(PenjualanDetailModel $transaksi): JsonResponse
	{
		$penjualan = PenjualanModel::find($transaksi->penjualan_id);
		$penjualanDetail = PenjualanDetailModel::with('barang')->where('penjualan_id', '=', $transaksi->penjualan_id)->get();
		return response()->json([
			'success' => true,
			'Transaksi' => $penjualan,
			'Detail Transaksi' => $penjualanDetail,
		]);
	}}