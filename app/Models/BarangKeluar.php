<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BarangKeluar extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'barang_id',
        'nama_barang',
        'kuantitas',
        'harga_satuan',
        'subtotal',
        'satuan_id',
        'jenis',
        'keterangan',
        'tanggal_keluar',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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
