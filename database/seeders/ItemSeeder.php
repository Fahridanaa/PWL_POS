<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barangData = [];

        for ($i = 1; $i <= 5; $i++) {
            $barangData[] = [
                'kategori_id' => $i,
                'barang_kode' => 'BRG00' . $i,
                'barang_name' => 'Barang ' . $i,
                'harga_beli' => rand(1, 100) * 1000,
                'harga_jual' => rand(1, 200) * 1000,
            ];
        }

        foreach ($barangData as $barang) {
            DB::table('m_barang')->insert($barang);
        }
    }
}
