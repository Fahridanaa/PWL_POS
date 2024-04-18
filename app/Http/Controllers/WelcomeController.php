<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WelcomeController extends Controller
{
	public function index()
	{
		$response = Http::get('api.giphy.com/v1/gifs/search', [
			'api_key' => 'RRJO6GPr71RKBYPAJzC2Nmujkq7HoRe5',
			'q' => 'anime hello',
			'rating' => 'g'
		]);

		$breadcrumb = (object) [
			'title' => 'Selamat Datang',
			'list' => ['Home', 'Welcome']
		];

		$activeMenu = 'dashboard';


		// Check if the request was successful
		if ($response->successful()) {
			$apiData = json_decode($response->body(), true);
		} else {
			$apiData = null;
		}

		return view('welcome', ['item' => $apiData['data'][0], 'breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
	}
}
