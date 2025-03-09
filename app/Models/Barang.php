<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kategori_id',
        'kode',
        'nama',
        'satuan_id',
        'harga_beli',
        'harga_pokok',
        'harga_jual',
        'diskon_value',
        'stok_minimum',
        'stok',
        'pajak_id',
        'status',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }
    public function pajak()
    {
        return $this->belongsTo(Pajak::class);
    }
    public function detailPembelian()
    {
        return $this->hasMany(DetailPembelian::class);
    }
    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class);
    }
    public function konversi()
    {
        return $this->hasMany(Konversi::class);
    }
}
