<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            'kode' => 'C001',
            'nama' => 'John Doe',
            'alamat' => 'Jl. Merdeka No. 123',
            'telepon' => '081234567890',
            'email' => 'john@example.com',
            'tanggal_lahir' => '1990-01-01',
            'status' => 'active'
        ]);

        Customer::create([
            'kode' => 'C002',
            'nama' => 'Jane Smith',
            'alamat' => 'Jl. Sudirman No. 456',
            'telepon' => '082345678901',
            'email' => 'jane@example.com',
            'tanggal_lahir' => '1992-03-15',
            'status' => 'active'
        ]);

        Customer::create([
            'kode' => 'C003',
            'nama' => 'Mike Johnson',
            'alamat' => 'Jl. Gatot Subroto No. 789',
            'telepon' => '083456789012',
            'email' => 'mike@example.com',
            'tanggal_lahir' => '1988-07-22',
            'status' => 'active'
        ]);

        Customer::create([
            'kode' => 'C004',
            'nama' => 'Sarah Wilson',
            'alamat' => 'Jl. Asia Afrika No. 321',
            'telepon' => '084567890123',
            'email' => 'sarah@example.com',
            'tanggal_lahir' => '1995-11-30',
            'status' => 'active'
        ]);

        Customer::create([
            'kode' => 'C005',
            'nama' => 'David Lee',
            'alamat' => 'Jl. Diponegoro No. 654',
            'telepon' => '085678901234',
            'email' => 'david@example.com',
            'tanggal_lahir' => '1987-09-18',
            'status' => 'active'
        ]);

        Customer::create([
            'kode' => 'C006',
            'nama' => 'Linda Chen',
            'alamat' => 'Jl. Veteran No. 987',
            'telepon' => '086789012345',
            'email' => 'linda@example.com',
            'tanggal_lahir' => '1993-05-25',
            'status' => 'active'
        ]);

        Customer::create([
            'kode' => 'C007',
            'nama' => 'Robert Taylor',
            'alamat' => 'Jl. Pahlawan No. 147',
            'telepon' => '087890123456',
            'email' => 'robert@example.com',
            'tanggal_lahir' => '1991-12-08',
            'status' => 'active'
        ]);

        Customer::create([
            'kode' => 'C008',
            'nama' => 'Maria Garcia',
            'alamat' => 'Jl. Imam Bonjol No. 258',
            'telepon' => '088901234567',
            'email' => 'maria@example.com',
            'tanggal_lahir' => '1994-02-14',
            'status' => 'active'
        ]);

        Customer::create([
            'kode' => 'C009',
            'nama' => 'James Brown',
            'alamat' => 'Jl. Thamrin No. 369',
            'telepon' => '089012345678',
            'email' => 'james@example.com',
            'tanggal_lahir' => '1989-08-03',
            'status' => 'active'
        ]);

        Customer::create([
            'kode' => 'C010',
            'nama' => 'Emily Wong',
            'alamat' => 'Jl. Hayam Wuruk No. 741',
            'telepon' => '081123456789',
            'email' => 'emily@example.com',
            'tanggal_lahir' => '1996-04-19',
            'status' => 'active'
        ]);
    }
}
