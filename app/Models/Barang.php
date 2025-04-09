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
        'markup',
        'diskon_value',
        'diskon_nominal',
        'stok_minimum',
        'stok',
        'pajak_id',
        'status',
        'gambar',
    ];

    public function getFinalPriceAttribute()
    {
        $harga_jual = $this->harga_jual;
        $diskon = ($harga_jual * $this->diskon_value) / 100;
        $harga_after_diskon = $harga_jual - $diskon;
        $pajak_amount = $this->pajak ? ($harga_after_diskon * $this->pajak->value) / 100 : 0;
        return $harga_after_diskon + $pajak_amount;
    }
    public function getHargaRealAttribute()
    {
        $harga_pokok = $this->detailPembelian()->latest()->first()?->harga_pokok ?? 0;
        $markup_percentage = $this->markup;
        $markup_amount = ($harga_pokok * $markup_percentage) / 100;
        return $harga_pokok + $markup_amount;
    }

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
