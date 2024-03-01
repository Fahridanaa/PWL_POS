<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['kategori_kode' => 'KAT001', 'kategori_nama' => 'Kategori Barang 1'],
            ['kategori_kode' => 'KAT002', 'kategori_nama' => 'Kategori Barang 2'],
            ['kategori_kode' => 'KAT003', 'kategori_nama' => 'Kategori Barang 3'],
            ['kategori_kode' => 'KAT004', 'kategori_nama' => 'Kategori Barang 4'],
            ['kategori_kode' => 'KAT005', 'kategori_nama' => 'Kategori Barang 5'],
        ];

        foreach ($data as $kategori) {
            DB::table('m_kategori')->insert($kategori);
        }
    }
}
