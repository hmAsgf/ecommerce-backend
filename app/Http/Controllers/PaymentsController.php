<?php

namespace App\Http\Controllers;

use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\Payments;
use App\Models\UsersProfile;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentsController extends Controller
{
    public static function getSnapToken($id)
    {
        try {
            Config::$serverKey = config('services.midtrans.server_key');
            Config::$isProduction = !config('services.midtrans.is_sandbox');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $order = Orders::getOrderById($id);
            $user = UsersProfile::getById($order->user_id);

            $params = array(
                'transaction_details' => array(
                    'order_id' => $order->id,
                    'gross_amount' => (int)$order->total['total'],
                ),
                'customer_details' => array(
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone_number,
                )
            );

            $snapUrl = Snap::getSnapUrl($params);

            return $snapUrl;

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Terjadi Kesalahan!',
                'error' => $th->getMessage(),
                'status' => false,
            ], 400);
        }
    }

    public function callback(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if($hashed == $request->signature_key) {
            $order = Orders::getOrderById($request->order_id);
            $payment = Payments::insert([
                'order_id' => $request->order_id,
                'user_id' => $order->user_id,
                'payment_time' => $request->transaction_time,
                'payment_type' => $request->payment_type,
                'amount' => $request->gross_amount,
                'status' => 'pending',
            ]);

            // pembayaran berhasil
            if($request->fraud_status == 'accept') {
                $order->update(['status' => 'paid']);
                $payment->update(['status' => 'success']);
            }
            else if($request->fraud_status == 'deny') {
                $order->update(['status' => 'failed']);
                $payment->update(['status' => 'failed']);
            }
        }
    }
}
