<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanModel extends Model
{
    use HasFactory;
    protected $table = 'penjualan';
    protected $fillable = [
        'id',
        'company',
        'kode_penjualan',
        'tanggal_transaksi',
        'id_customer',
        'product_id',
        'nama_product',
        'qty',
        'hjual'
    ];
}
