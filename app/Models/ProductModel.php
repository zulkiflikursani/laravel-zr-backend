<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    use HasFactory;
    protected $table = "product";

    protected $fillable = [
        'id',
        'company',
        'nama',
        'desc',
        'kat',
        'hbeli',
        'hjual',
        'nama',
    ];
    protected $casts = [
        'hjual' => 'integer',
        'hbeli' => 'integer',

    ];
}
