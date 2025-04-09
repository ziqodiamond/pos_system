<?php

namespace Database\Seeders;

use App\Models\Pajak;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PajakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pajak::create([
            'kode' => 'PPN',
            'nama' => 'Pajak Pertambahan Nilai',
            'persen' => 11,
            'status' => 'active'
        ]);
        Pajak::create([
            'kode' => 'No Tax',
            'nama' => 'Tanpa Pajak',
            'persen' => 0,
            'status' => 'active'
        ]);
    }
}
