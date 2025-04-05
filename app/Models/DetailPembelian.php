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
        'satuan_dasar_id',
        'harga_satuan',
        'harga_diskon',
        'harga_pokok',
        'other_cost',
        'diskon_value',
        'pajak_value',
        'subtotal',
        'total',
        'stok',
        'status',
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
    public function satuanDasar()
    {
        return $this->belongsTo(Satuan::class, 'satuan_dasar_id');
    }
}
