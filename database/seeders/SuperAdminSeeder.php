<?php

namespace Database\Seeders;


use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon; // Pastikan untuk mengimpor Carbon

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin12345'), // Hash the password
            'role' => 'super_admin', // Set the role to super_admin
            'email_verified_at' => Carbon::now(), // Set email_verified_at to current timestamp
        ]);
    }
}
