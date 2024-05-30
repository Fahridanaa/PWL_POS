<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\JsonResponse;

class LogoutController extends Controller
{
	public function __invoke(): JsonResponse
	{
		$removeToken = JWTAuth::invalidate(JWTAuth::getToken());

		if ($removeToken) {
			return response()->json([
				'success' => true,
				'message' => 'Logout Berhasil!'
			]);
		}
		return response()->json([
			'success' => false,
			'message' => 'Logout Gagal!',
		], 422);
	}
}