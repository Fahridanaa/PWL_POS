<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() {
//	    $data = [
//			'username' => 'customer-1',
//		    'nama' => 'Pelanggan',
//		    'password' => Hash::make('12345'),
//		    'level_id' => 4
//	    ];
//		UserModel::insert($data);
//
//	    $data = [
//			'nama' => 'Pelanggan Pertama',
//	    ];
//		UserModel::where('username', 'customer-1')->update($data);

//	    $data = [
//		    'level_id' => 2,
//		    'username' => 'manager_dua',
//		    'name' =>'Manager 2',
//		    'password' => Hash::make('12345')
//	    ];
//
//	    $data = [
//		    'level_id' => 2,
//		    'username' => 'manager_tiga',
//		    'name' =>'Manager 3',
//		    'password' => Hash::make('12345')
//	    ];
//		UserModel::create($data);

//		$user = UserModel::all();
//	    $user = UserModel::findOr(20, ['username', 'name'], function () {
//			abort(404);
//	    });
//	    $user = UserModel::findOrFail(1);
//	    $user = UserModel::where('username', 'manager9')->firstOrFail();
//	    $user = UserModel::where('level_id', 2)->count();

//	    $user = UserModel::firstOrCreate(
//			[
//				'username' => 'manager',
//				'name' => 'Manager'
//			]
//	    );

//	    $user = UserModel::firstOrCreate(
//			[
//				'username' => 'manager22',
//				'name' => 'Manager Dua Dua',
//				'password' => Hash::make('12345'),
//				'level_id' => 2
//			]
//	    );

//	    $user = UserModel::firstOrNew(
//			[
//				'username' => 'manager',
//				'name' => 'Manager'
//			],
//	    );

//	    $user = UserModel::firstOrNew(
//			[
//				'username' => 'manager33',
//				'name' => 'Manager Tiga Tiga',
//				'password' => Hash::make('12345'),
//				'level_id' => 2
//			]
//	    );
//		$user->save();
//		return view('user', ['data' => $user]);


//	    $user = UserModel::create([
//			'username' => 'manager55',
//		    'name' => 'Manager55',
//		    'password' => Hash::make('12345'),
//		    'level_id' => 2,
//	    ]);
//
//		$user->username = 'manager56';
//
//		$user->isDirty();
//		$user->isDirty('username');
//		$user->isDirty('name');
//		$user->isDirty(['name', 'username']);
//
//		$user->isClean();
//		$user->isClean('username');
//		$user->isClean('name');
//		$user->isClean(['name', 'username']);
//
//		$user->save();
//
//		$user->isDirty();
//		$user->isClean();
//		dd($user->isDirty());

	    $user = UserModel::create([
			'username' => 'manager11',
		    'name' => 'Manager11',
		    'password' => Hash::make('12345'),
		    'level_id' => 2
	    ]);

		$user->username = 'manager12';

		$user->save();

		$user->wasChanged();
		$user->wasChanged('username');
		$user->wasChanged(['username', 'level_id']);
		$user->wasChanged('name');
		dd($user->wasChanged(['name', 'username']));

    }
}
