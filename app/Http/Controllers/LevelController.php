<?php

namespace App\Http\Controllers;

use App\DataTables\LevelDataTable;
use App\Http\Requests\StoreLevelRequest;
use App\Models\LevelModel;

class LevelController extends Controller
{
    public function index(LevelDataTable $dataTable) {
		return $dataTable->render('level.index');
    }

	public function create()
	{
		return view('kategori.create');
	}

	function store(StoreLevelRequest $request)
	{
		$validated = $request->safe()->only(['level_kode', 'level_nama']);

		LevelModel::create($validated);
		return redirect('/level');
	}

	public function update($id)
	{
		$level = LevelModel::find($id);
		return view('level.update', ['data' => $level]);
	}

	public function hapus($id)
	{
		$kategori = LevelModel::find($id);
		$kategori->delete();

		return redirect('/kategori');
	}
}
