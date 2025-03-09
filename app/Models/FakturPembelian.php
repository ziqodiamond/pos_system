<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FakturPembelian extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'supplier_id',
        'no_faktur',
        'tanggal_faktur',
        'deskripsi',
        'subtotal',
        'biaya_lainnya',
        'diskon_mode',
        'diskon_value',
        'pajak_id',
        'pajak_value',
        'total_tagihan',
        'total_bayar',
        'total_hutang',
        'status',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function pajak()
    {
        return $this->belongsTo(Pajak::class);
    }
    public function pembelian()
    {
        return $this->hasMany(Pembelian::class);
    }
    public function pembayaranFaktur()
    {
        return $this->hasMany(PembayaranFaktur::class);
    }
}
