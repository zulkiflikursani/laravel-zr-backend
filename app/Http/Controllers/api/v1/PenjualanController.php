<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\PenjualanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

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

    public function store(Request $request)
    {
        $rules = [
            "*.company" => "required",
            "*.kode_penjualan" => "required",
            '*.tanggal_transaksi' => 'required',
            '*.id_customer' => '',
            "*.product_id" => "required",
            '*.nama_product' => 'required',
            '*.qty' => 'required',
            '*.hjual' => 'required'
        ];
        $message =  [
            "company.required" => "Nama Perusahaan tidak ditentukan",
            "kode_penjualan.required" => "Silahkan Masukkan kode penjualan",
            'tanggal_transaksi.required' => 'Silahkan masukkan tanggal transaksi',
            'id_customer.required' => 'Silahkan masukakan kode kostumer',
            "product_id.required" => "Silahkan masukkan product id",
            'nama_product.required' => 'Silahkan masukkan nama product',
            'qty.required' => 'Silahkan masukkan harga jual',
            'hjual.required' => 'Silahkan masukkan harga jual'

        ];

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response([
                'errors' => $validator->errors()
            ]);
        } else {
            $requsetData = $request->json()->all();
            DB::beginTransaction();
            try {
                foreach ($requsetData as $row) {
                    $company = $row['company'];
                    $kode_penjualan = $row['kode_penjualan'];
                    $tanggal_transaksi = $row['tanggal_transaksi'];
                    $id_customer = $row['id_customer'];
                    $product_id = $row['product_id'];
                    $nama_product = $row['nama_product'];
                    $qty = $row['qty'];
                    $hjual = $row['hjual'];

                    PenjualanModel::create([
                        "company" => $company,
                        "kode_penjualan" => $kode_penjualan,
                        'tanggal_transaksi' => $tanggal_transaksi,
                        'id_customer' => $id_customer,
                        "product_id" => $product_id,
                        'nama_product' => $nama_product,
                        'qty' => $qty,
                        'hjual' => $hjual
                    ]);
                };
                DB::commit();
                return Response([
                    'message' => 'Berhasil Menyimpan data penjualan',
                    'status' => 'ok'
                ]);
            } catch (QueryException $e) { // Catch QueryException
                // If a query exception occurs, rollback the transaction
                DB::rollback();

                // Log the exception for debugging purposes
                logger()->error('QueryException occurred during transaction: ' . $e->getMessage());

                // Return an error response
                return response()->json(['error' => 'QueryException occurred. Please check the logs for details.'], 500);
            } catch (\Exception $e) {
                // If an exception occurred, rollback the transaction
                DB::rollback();

                // Optionally, log the exception or return an error response
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        return;
        // return $request;
        // $request->validate([
        //     "company" => "required",
        //     "kode_penjualan" => "required",
        //     'tanggal_transaksi' => 'required',
        //     'id_customer' => '',
        //     "product_id" => "required",
        //     'nama_product' => 'required',
        //     'qty' => 'required',
        //     'hjual' => 'required'
        // ], [
        //     "company.required" => "Nama Perusahaan tidak ditentukan",
        //     "kode_penjualan.required" => "Silahkan Masukkan kode penjualan",
        //     'tanggal_transaksi.required' => 'Silahkan masukkan tanggal transaksi',
        //     'id_customer.required' => 'Silahkan masukakan kode kostumer',
        //     "product_id.required" => "Silahkan masukkan product id",
        //     'nama_product.required' => 'Silahkan masukkan nama product',
        //     'qty.required' => 'Silahkan masukkan harga jual',
        //     'hjual.required' => 'Silahkan masukkan harga jual'

        // ]);

        // $Penjualan = PenjualanModel::create([
        //     "company" => $request->input('company'),
        //     "kode_penjualan" => $request->input('kode_penjualan'),
        //     'tanggal_transaksi' => $request->input('tanggal_transaksi'),
        //     'id_customer' => $request->input('id_customer'),
        //     "product_id" => $request->input('product_id'),
        //     'nama_product' => $request->input('nama_product'),
        //     'qty' => $request->input('qty'),
        //     'hjual' => $request->input('hjual')
        // ]);
        // if ($Penjualan) {
        //     return Response([
        //         'message' => 'Berhasil Menyimpan Penjualan',
        //         'status' => 'ok'
        //     ]);
        // } else {
        //     return ([
        //         'message' => "Gagal Menyimpan Data",
        //         'status' => 'gagal'

        //     ]);
        // }
    }

    public function PenjualanByKodePenjualan($id)
    {
        $Penjualan = PenjualanModel::where('kode_penjualan', $id)->get();

        return $Penjualan;
    }
}
