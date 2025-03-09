<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembelian extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_ref',
        'tanggal_pembelian',
        'tanggal_masuk',
        'supplier_id',
        'user_id',
        'deskripsi',
        'subtotal',
        'diskon_mode',
        'diskon_value',
        'pajak_id',
        'pajak_value',
        'biaya_lainnya',
        'total',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function pajak()
    {
        return $this->belongsTo(Pajak::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function details()
    {
        return $this->hasMany(DetailPembelian::class);
    }
    public function faktur()
    {
        return $this->belongsTo(FakturPembelian::class);
    }
}
