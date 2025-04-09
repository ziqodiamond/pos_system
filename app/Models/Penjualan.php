<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penjualan extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_ref',
        'kasir_id',
        'customer_id',
        'subtotal',
        'total_diskon',
        'total_pajak',
        'dpp',
        'grand_total',
        'total_bayar',
        'kembalian',
        'metode_pembayaran',
    ];

    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function details()
    {
        return $this->hasMany(DetailPenjualan::class);
    }
    public function pajak()
    {
        return $this->belongsTo(Pajak::class);
    }
    public function retur()
    {
        return $this->hasOne(ReturPenjualan::class);
    }
    public function getTotalItemAttribute()
    {
        // Menghitung jumlah detail pembelian yang terkait
        return $this->details()->count();
    }
    public static function formatRupiah($nilai)
    {
        // Konversi nilai sen menjadi rupiah (dibagi 100)
        $rupiahValue = $nilai / 100;

        // Format dengan pemisah ribuan titik dan desimal koma
        return number_format($rupiahValue, 2, ',', '.');
    }
}
