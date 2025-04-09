<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FakturPembelian extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'pembelian_id',
        'supplier_id',
        'no_faktur',
        'tanggal_faktur',
        'deskripsi',
        'subtotal',
        'biaya_lainnya',
        'diskon_mode',
        'diskon_value',
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
        return $this->belongsTo(Pembelian::class);
    }
    public function pembayaranFaktur()
    {
        return $this->hasMany(PembayaranFaktur::class);
    }
}
