<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Supplier::create([
            'kode' => 'SUP001',
            'npwp' => '12.345.678.9-123.000',
            'nama' => 'PT Maju Jaya',
            'alamat' => 'Jl. Raya Utama No. 123',
            'kota' => 'Jakarta',
            'kontak' => '021-5555555',
            'email' => 'info@majujaya.com',
            'catatan' => 'Supplier elektronik',
            'status' => 'active'
        ]);

        Supplier::create([
            'kode' => 'SUP002',
            'npwp' => '98.765.432.1-456.000',
            'nama' => 'CV Sukses Makmur',
            'alamat' => 'Jl. Industri No. 45',
            'kota' => 'Surabaya',
            'kontak' => '031-4444444',
            'email' => 'contact@suksesmakmur.com',
            'catatan' => 'Supplier makanan',
            'status' => 'active'
        ]);

        Supplier::create([
            'kode' => 'SUP003',
            'npwp' => '45.678.912.3-789.000',
            'nama' => 'UD Sejahtera',
            'alamat' => 'Jl. Pasar Baru No. 78',
            'kota' => 'Bandung',
            'kontak' => '022-3333333',
            'email' => 'info@sejahtera.com',
            'catatan' => 'Supplier peralatan',
            'status' => 'active'
        ]);
    }
}
