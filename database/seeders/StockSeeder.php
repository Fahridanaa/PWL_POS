<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        for ($i = 1; $i <= 5; $i++) {
            DB::table('t_stok')->insert([
                'barang_id' => $i,
                'user_id' => rand(1,3),
                'stok_tanggal' => Carbon::now(),
                'stok_jumlah' => rand(1, 100),
            ]);
        }
    }
}
