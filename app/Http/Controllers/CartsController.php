<?php

namespace App\Http\Controllers;

use App\Models\CartItems;
use App\Models\Carts;
use Illuminate\Http\Request;

class CartsController extends Controller
{
    public function show($id)
    {
        $cartItems = CartItems::getItemsByUserId($id);

        return response()->json([
            'items' => $cartItems,
            'status' => true,
        ]);
    }

    public function store(Request $request)
    {
        $validator = CartItems::validate($request->all());

        if($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'status' => false,
            ], 400);
        }

        $item = CartItems::insert($request);

        if(!$item) {
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
        $validator = CartItems::validate($request->all());

        if($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'status' => false,
            ], 400);
        }

        $item = CartItems::modify($id, $request);

        if(!$item) {
            return response()->json([
                'message' => 'Produk gagal diubah!',
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
        $item =  CartItems::remove($id);

        if(!$item){
            return response()->json()([
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
