<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\POSController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('/level')->group(function() {
	Route::get('/', [LevelController::class, 'index'])->name('/level');
	Route::get('/create', [LevelController::class, 'create'])->name('/level/create');
	Route::get('/hapus', [LevelController::class, 'hapus'])->name('/level/hapus');
	Route::get('/update', [LevelController::class, 'update'])->name('/level/update');
});

Route::prefix('/kategori')->group(function () {
	Route::get('/', [KategoriController::class, 'index'])->name('/kategori');
	Route::get('/create', [KategoriController::class, 'create'])->name('/kategori/create');
	Route::post('/', [KategoriController::class, 'store']);
	Route::get('/update/{id}', [KategoriController::class, 'update'])->name('/kategori/update');
	Route::put('/update_simpan/{id}', [KategoriController::class, 'update_simpan'])->name('/kategori/update_simpan');
	Route::get('/hapus/{id}', [KategoriController::class, 'hapus'])->name('/kategori/hapus');
});

//Route::prefix('/user')->group(function () {
//	Route::get('/', [UserController::class, 'index'])->name('/user');
//	Route::get('/tambah', [UserController::class, 'tambah'])->name('/user/tambah');
//	Route::post('/tambah_simpan', [UserController::class, 'tambah_simpan'])->name('/user/tambah_simpan');
//	Route::get('/ubah/{id}', [UserController::class, 'ubah'])->name('/user/ubah');
//	Route::put('/ubah_simpan/{id}', [UserController::class, 'ubah_simpan'])->name('/user/ubah_simpan');
//	Route::get('/hapus/{id}', [UserController::class, 'hapus'])->name('/user/hapus');
//});

Route::resource('m_user', POSController::class);
