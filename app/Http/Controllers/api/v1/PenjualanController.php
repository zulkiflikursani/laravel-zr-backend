<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\PenjualanModel;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    //
    public function index()
    {
        return "Hello";
    }

    public function PenjualanByDate($date, $company)
    {
        $Penjualan = PenjualanModel::whereDate('tanggal_transaksi', $date)
            ->where('company', $company)->get();

        return $Penjualan;
    }
}
