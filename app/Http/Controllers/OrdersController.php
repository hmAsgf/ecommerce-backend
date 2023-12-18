<?php

namespace App\Http\Controllers;

use App\Models\CartItems;
use App\Models\Orders;
use App\Models\OrderItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    public function show($id)
    {
        $orders = Orders::getOrders($id);

        return response()->json([
            'orders' => $orders,
            'status' => true,
        ]);
    }

    public function store(Request $request)
    {
        // dd($request);
        $validatorOrders = Orders::validate($request->all());
        if($validatorOrders->fails()) {
            return response()->json([
                'message' => $validatorOrders->errors()->first(),
                'status' => false,
            ], 400);
        }

        foreach($request->items as $item) {
            $validatorOrderItems = OrderItems::validate($item);
            if($validatorOrderItems->fails()) {
                return response()->json([
                    'message' => $validatorOrderItems->errors()->first(),
                    'status' => false,
                ], 400);
            }
        }

        try {
            DB::beginTransaction();
            $order = Orders::insert($request);
            OrderItems::insert($request['items'], $order->id);
            CartItems::removeByUserId($request['user_id']);
            DB::commit();

            return response()->json([
                'message' => 'Order berhasil ditambahkan!',
                'status' => true,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan!',
                'error' => $th->getMessage(),
                'status' => false,
            ], 400);
        }
    }
}
