<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LevelModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LevelController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(): Collection
	{
		return LevelModel::all();
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request): JsonResponse
	{
		return response()->json(LevelModel::create($request->all()), 201);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(LevelModel $level): LevelModel|Collection|array|null
	{
		return LevelModel::find($level);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, LevelModel $level): LevelModel|Collection|array|null
	{
		$level->update($request->all());
		return LevelModel::find($level);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(LevelModel $level): JsonResponse
	{
		$level->delete();

		return response()->json([
			'success' => true,
			'message' => 'Data Terhapus'
		]);
	}
}