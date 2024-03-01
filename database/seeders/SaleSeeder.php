<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            DB::table('t_penjualan')->insert([
                'user_id' => rand(1,3),
                'pembeli' => 'Customer ' . $i,
                'penjualan_kode' => 'S00' . $i,
                'penjualan_tanggal' => Carbon::now(),
            ]);
        }
    }
}
