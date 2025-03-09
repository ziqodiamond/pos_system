<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailReturPenjualan extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'retur_penjualan_id',
        'barang_id',
        'nama_barang',
        'kuantitas',
        'satuan_id',
        'harga_satuan',
        'subtotal',
        'kondisi',
        'keterangan',
    ];

    public function returPenjualan()
    {
        return $this->belongsTo(ReturPenjualan::class);
    }
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }
}
