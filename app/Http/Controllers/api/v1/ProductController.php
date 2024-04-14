<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\ProductModel;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    //
    public function index()
    {
        return ProductModel::all();
    }

    public function productCompany(string $company)
    {
        if ($company != '' || $company != null) {
            $products = ProductModel::where('company', $company)->get();
            return $products;
        } else {
            return null;
        }
    }
    public function productById(string $id, string $company)
    {
        $product = ProductModel::where('id', $id)
            ->where('company', $company)->get();
        return $product;
    }
    public function insert(Request $request)
    {
        $request->validate([
            "company" => 'required',
            "nama" => 'required',
            "desc" => 'required',
            "kat" => 'required',
            "hjual" => 'required',
            "hbeli" => 'required',
        ], [
            'company.required' => "Silahkan mengisi kolom perusahaan",
            'nama.required' => "Silahkan masukkan nama produk.",
            'desc.required' => "Silahkan deskripsi produk.",
            'kat.required' => "Silahkan mengisi katori produk",
            'hjual.required' => "Silahkan mengisi harga jual produk",
            'hbeli.required' => "Silahkan mengisi harga beli produk",
        ]);

        $products = ProductModel::create([
            'nama' => $request->input('nama'),
            'company' => $request->input('company'),
            'desc' => $request->input('desc'),
            'kat' => $request->input('kat'),
            'hjual' => $request->input('hjual'),
            'hbeli' => $request->input('hbeli')
        ]);
        if ($products) {
            return Response([
                "message" => "Berhasil Menyimpan Product",
                // "data" => $products
            ]);
        } else {
            return Response([
                "message" => "Gagal Menyimpan Product",
                "error" => "Gagal"
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $dataRequest = $request->all();

        unset($dataRequest['_method']);
        $request->validate([
            "company" => 'required',
            "nama" => 'required',
            "desc" => 'required',
            "kat" => 'required',
            "hjual" => 'required',
            "hbeli" => 'required',
        ], [
            'company.required' => "Silahkan mengisi kolom perusahaan",
            'nama.required' => "Silahkan masukkan nama produk.",
            'desc.required' => "Silahkan deskripsi produk.",
            'kat.required' => "Silahkan mengisi katori produk",
            'hjual.required' => "Silahkan mengisi harga jual produk",
            'hbeli.required' => "Silahkan mengisi harga beli produk",
        ]);
        $product = ProductModel::find($id);
        $product->update([
            'nama' => $request->input('nama'),
            'company' => $request->input('company'),
            'desc' => $request->input('desc'),
            'kat' => $request->input('kat'),
            'hjual' => $request->input('hjual'),
            'hbeli' => $request->input('hbeli')
        ]);
        return $product;
    }

    public function delete(string $id)
    {
        $delete = ProductModel::where('id', $id)->delete();
        if ($delete) {
            return Response([
                "status" => "ok",
                "message" => "Berhasil Menghapus Data Product",
            ]);
        } else {
            return Response([
                "message" => "Gagal Menghapus Data Product",
                "status" => "gagal"
            ]);
        };
    }
}
