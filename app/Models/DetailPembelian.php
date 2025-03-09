<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailPembelian extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'pembelian_id',
        'barang_id',
        'kuantitas',
        'satuan_id',
        'harga_satuan',
        'subtotal',
        'pajak_id',
        'pajak_value',
        'stok',
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }
    public function pajak()
    {
        return $this->belongsTo(Pajak::class);
    }
}
