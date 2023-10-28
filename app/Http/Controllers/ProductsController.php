<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index()
    {
        return response()->json([
            'products' => Products::getAll(),
            'status' => true,
        ]);
    }

    public function show($id)
    {
        $product = Products::getById($id);

        if(!$product) {
            return response()->json([
                'message' => 'Produk tidak ditemukan!',
                'status' => false,
            ], 400);
        }

        return response()->json([
            'product' => $product,
            'status' => true,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Products::validate($request->all());

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()->first(),
                'status' => false,
            ], 400);
        }

        $product = Products::insert($request);

        if(!$product){
            return response()->json([
                'message' => 'Produk gagal ditambahkan!',
                'status' => false,
            ], 400);
        }

        return response()->json([
            'message' => 'Produk berhasil ditambahkan!',
            'status' => true,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Products::validate($request->all(), $id);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()->first(),
                'status' => false
            ], 400);
        }

        $product = Products::modify($id, $request);

        if(!$product){
            return response()->json([
                'message' => 'Produk gagal ditambah!',
                'status' => false,
            ], 400);
        }

        return response()->json([
            'message' => 'Produk berhasil diubah!',
            'status' => true,
        ]);
    }

    public function destroy($id)
    {
        $product = Products::remove($id);

        if(!$product){
            return response()->json([
                'message' => 'Produk gagal dihapus!',
                'status' => false,
            ], 400);
        }

        return response()->json([
            'message' => 'Produk berhasil dihapus!',
            'status' => true,
        ]);
    }
}
