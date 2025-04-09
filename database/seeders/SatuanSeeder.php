<?php

namespace Database\Seeders;

use App\Models\Satuan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SatuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Satuan::create([
            'kode' => 'PCS',
            'nama' => 'Pieces',
            'status_satuan' => 'satuan_dasar',
            'keterangan' => 'Satuan per pieces',
            'status' => 'active'
        ]);

        Satuan::create([
            'kode' => 'BOX',
            'nama' => 'Box',
            'status_satuan' => 'konversi_satuan',
            'keterangan' => 'Satuan per box',
            'status' => 'active'
        ]);

        Satuan::create([
            'kode' => 'KG',
            'nama' => 'Kilogram',
            'status_satuan' => 'satuan_dasar',
            'keterangan' => 'Satuan berat kilogram',
            'status' => 'active'
        ]);

        Satuan::create([
            'kode' => 'LTR',
            'nama' => 'Liter',
            'status_satuan' => 'satuan_dasar',
            'keterangan' => 'Satuan volume liter',
            'status' => 'active'
        ]);

        Satuan::create([
            'kode' => 'PAK',
            'nama' => 'Pack',
            'status_satuan' => 'konversi_satuan',
            'keterangan' => 'Satuan per pack',
            'status' => 'active',
            'keterangan' => 'Satuan per pack',
            'status' => 'active'
        ]);
    }
}
