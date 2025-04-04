<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{

    use HasFactory, SoftDeletes, HasUuids;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode',
        'npwp',
        'nama',
        'alamat',
        'kota',
        'kontak',
        'email',
        'catatan',
        'status',
    ];

    public function pembelian()
    {
        return $this->hasMany(Pembelian::class);
    }
}
