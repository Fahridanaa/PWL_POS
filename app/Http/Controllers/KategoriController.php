<?php

namespace App\Http\Controllers;

use App\DataTables\KategoriDataTable;
use App\Http\Requests\StorePostRequest;
use App\Models\KategoriModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
	public function index(KategoriDataTable $dataTable)
	{
		return $dataTable->render('kategori.index');
	}

	public function create()
	{
		return view('kategori.create');
	}

	public function store(StorePostRequest $request): RedirectResponse
	{
		$validated = $request->validated();

		$validated = $request->safe()->only(['kategori_kode', 'kategori_nama']);
		$validated = $request->safe()->except(['kategori_kode', 'kategori_nama']);

		return redirect('/kategori');
	}

	public function update($id)
	{
		$kategori = KategoriModel::find($id);
		return view('kategori.update', ['data' => $kategori]);
	}

	public function update_simpan($id, Request $request)
	{
		$request->validate([
			'kodeKategori' => 'required',
			'namaKategori' => 'required',
		]);

		$kategori = KategoriModel::findOrFail($id);

		$kategori->kategori_kode = $request->kodeKategori;
		$kategori->kategori_nama = $request->namaKategori;

		$kategori->save();
		return redirect('/kategori')->with('success', 'Kategori berhasil diperbarui.');
	}
	public function hapus($id)
	{
		$kategori = KategoriModel::find($id);
		$kategori->delete();

		return redirect('/kategori');
	}
}