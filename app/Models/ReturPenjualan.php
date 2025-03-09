<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturPenjualan extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'penjualan_id',
        'tanggal_retur',
        'total_retur',
        'metode_return'
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }
    public function details()
    {
        return $this->hasMany(DetailReturPenjualan::class);
    }
}
