<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index()
    {
        return response()->json([
            'products' => Products::getProducts(),
            'status' => true,
        ]);
    }

    public function show($id)
    {
        $product = Products::getProductById($id);

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
}
