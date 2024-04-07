<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianModel extends Model
{
    use HasFactory;
    protected $table = 'pembelian';
    protected $fillable = [
        'id',
        'company',
        'kode_pembelian',
        'tanggal_transaksi',
        'id_customer',
        'product_id',
        'nama_product',
        'qty',
        'hbeli'
    ];
}
