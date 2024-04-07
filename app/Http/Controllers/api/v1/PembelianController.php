<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\PembelianModel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PembelianController extends Controller
{
    public function PembelianByDate($date, $company)
    {
        $Penjualan = PembelianModel::whereDate('tanggal_transaksi', $date)
            ->where('company', $company)->get();

        return $Penjualan;
    }
    //
    public function store(Request $request)
    {
        $rules = [
            "*.company" => "required",
            "*.kode_pembelian" => "required",
            '*.tanggal_transaksi' => 'required',
            '*.id_customer' => '',
            "*.product_id" => "required",
            '*.nama_product' => 'required',
            '*.qty' => 'required',
            '*.hbeli' => 'required'
        ];
        $message =  [
            "company.required" => "Nama Perusahaan tidak ditentukan",
            "kode_pembelian.required" => "Silahkan Masukkan kode penjualan",
            'tanggal_transaksi.required' => 'Silahkan masukkan tanggal transaksi',
            'id_customer.required' => 'Silahkan masukakan kode kostumer',
            "product_id.required" => "Silahkan masukkan product id",
            'nama_product.required' => 'Silahkan masukkan nama product',
            'qty.required' => 'Silahkan masukkan kuantitas',
            'hbeli.required' => 'Silahkan masukkan harga beli'

        ];

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response([
                'errors' => $validator->errors()
            ]);
        } else {
            $requestData = $request->json()->all();
            $kode_pembelian_generate = Str::random(5);
            DB::beginTransaction();
            try {
                foreach ($requestData as $row) {
                    $company = $row['company'];
                    $kode_pembelian = $kode_pembelian_generate;
                    $tanggal_transaksi = $row['tanggal_transaksi'];
                    $id_customer = $row['id_customer'];
                    $product_id = $row['product_id'];
                    $nama_product = $row['nama_product'];
                    $qty = $row['qty'];
                    $hbeli = $row['hbeli'];

                    PembelianModel::create([
                        "company" => $company,
                        "kode_pembelian" => $kode_pembelian,
                        'tanggal_transaksi' => $tanggal_transaksi,
                        'id_customer' => $id_customer,
                        "product_id" => $product_id,
                        'nama_product' => $nama_product,
                        'qty' => $qty,
                        'hbeli' => $hbeli
                    ]);
                };
                DB::commit();
                return Response([
                    'message' => 'Berhasil Menyimpan data pembelian ',
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
    }

    public function PembelianByKodePembelian($id)
    {
        $Pembelian = PembelianModel::where('kode_pembelian', $id)->get();

        return $Pembelian;
    }


    public function update(Request $request, $kode_pembelian)
    {

        $rules = [
            "*.company" => "required",
            "*.kode_pembelian" => "required",
            '*.tanggal_transaksi' => 'required',
            '*.id_customer' => '',
            "*.product_id" => "required",
            '*.nama_product' => 'required',
            '*.qty' => 'required',
            '*.hbeli' => 'required'
        ];
        $message =  [
            "company.required" => "Nama Perusahaan tidak ditentukan",
            "kode_pembelian.required" => "Silahkan Masukkan kode pembelian",
            'tanggal_transaksi.required' => 'Silahkan masukkan tanggal transaksi',
            'id_customer.required' => 'Silahkan masukakan kode kostumer',
            "product_id.required" => "Silahkan masukkan product id",
            'nama_product.required' => 'Silahkan masukkan nama product',
            'qty.required' => 'Silahkan masukkan kuantitas',
            'hbeli.required' => 'Silahkan masukkan harga beli'

        ];

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response([
                'errors' => $validator->errors()
            ]);
        } else {
            $requestData = $request->json()->all();
            DB::beginTransaction();
            try {
                $deletePembelian = PembelianModel::where('kode_pembelian', $kode_pembelian)->delete();
                if (!$deletePembelian) {
                    DB::rollBack();
                    return Response([
                        'message' => 'Kode Penjualan tidak ditemukan',
                        'status' => 'gagal'
                    ]);
                }
                foreach ($requestData as $row) {
                    $newData = PembelianModel::create($row);
                    if (!$newData) {
                        DB::rollBack();
                        return Response([
                            'message' => 'Gagal Mengupdate Data',
                            'status' => 'gagal'
                        ]);
                    }
                    // Update all found records with the data from the reque
                }
                DB::commit();
                return Response([
                    "message" => 'Berhasil Mengupdate Data Pembelian !',
                    "status" => 'ok',
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['error' => $e->getMessage()], 500);

                // return Response([
                //     'message' => 'gagal menupdate data ',
                //     'satatus' => 'gagal'
                // ]);
            }
        }
    }
}
