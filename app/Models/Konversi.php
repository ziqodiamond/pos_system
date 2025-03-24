<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konversi extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'barang_id',
        'satuan_Id',
        'nilai_konversi',
        'satuan_tujuan_id',

    ];
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }
    public function satuanTujuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_tujuan_id');
    }
}
