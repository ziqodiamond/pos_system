<?php

namespace Database\Seeders;

use App\Models\Kategori;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kategori::create([
            'kode' => 'KAT001',
            'nama' => 'Makanan',
            'status' => 'active'
        ]);

        Kategori::create([
            'kode' => 'KAT002',
            'nama' => 'Minuman',
            'status' => 'active'
        ]);

        Kategori::create([
            'kode' => 'KAT003',
            'nama' => 'Snack',
            'status' => 'active'
        ]);

        Kategori::create([
            'kode' => 'KAT004',
            'nama' => 'Alat Tulis',
            'status' => 'active'
        ]);

        Kategori::create([
            'kode' => 'KAT005',
            'nama' => 'Kebersihan',
            'status' => 'active'
        ]);
    }
}
