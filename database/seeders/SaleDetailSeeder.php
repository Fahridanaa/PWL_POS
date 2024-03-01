<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SaleDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            DB::table('t_penjualan_detail')->insert([
                'penjualan_id' => $i,
                'barang_id' => $i,
                'harga' => rand(1, 100) * 1000,
                'jumlah' => rand(1, 100),
            ]);
        }
    }
}
