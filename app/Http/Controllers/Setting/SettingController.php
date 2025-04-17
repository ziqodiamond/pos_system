<?php

namespace App\Http\Controllers\Setting;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    /**
     * Menampilkan halaman setting
     */
    public function index()
    {
        // Mengambil semua setting dari database
        $settings = Setting::pluck('value', 'key')->toArray();

        // Data default jika tidak ada di database
        $tokoData = [
            'toko_nama' => $settings['toko_nama'] ?? env('TOKO_NAMA', 'Nama Toko'),
            'toko_alamat' => $settings['toko_alamat'] ?? env('TOKO_ALAMAT', 'Alamat Toko'),
            'toko_kota' => $settings['toko_kota'] ?? env('TOKO_KOTA', 'Jakarta'),
            'toko_provinsi' => $settings['toko_provinsi'] ?? env('TOKO_PROVINSI', 'DKI Jakarta'),
            'toko_telepon' => $settings['toko_telepon'] ?? env('TOKO_TELEPON', 'Telepon Toko'),
            'toko_email' => $settings['toko_email'] ?? env('TOKO_EMAIL', 'Email Toko'),
            'toko_npwp' => $settings['toko_npwp'] ?? env('TOKO_NPWP', 'NPWP Toko'),
        ];

        return view('setting.index', compact('tokoData'));
    }

    /**
     * Menyimpan perubahan setting
     */
    public function update(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'toko_nama' => 'required|string|max:255',
            'toko_alamat' => 'required|string|max:255',
            'toko_kota' => 'required|string|max:100',
            'toko_provinsi' => 'required|string|max:100',
            'toko_telepon' => 'required|string|max:20',
            'toko_email' => 'required|email|max:100',
            'toko_npwp' => 'required|string|max:50',
        ]);

        // Menyimpan setiap setting ke database
        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'Pengaturan toko berhasil disimpan!');
    }
}
