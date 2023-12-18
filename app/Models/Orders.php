<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Orders extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $guarded = ['id'];
    protected $casts = [
        'total' => 'array'
    ];
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public static function validate($data)
    {
        $rules = [
            'user_id' => 'required',
            'date' => 'required',
            'total' => 'required',
            'status' => 'required',
        ];

        return Validator::make($data, $rules);
    }

    public static function getOrders($userId,)
    {
        return self::select('id', 'date', 'total', 'status')
                    ->where('user_id', $userId)
                    ->orderBy('date', 'DESC')
                    ->get();
    }

    public static function insert($order)
    {
        return self::query()->create($order->all());
    }

    public static function modify($id, $order)
    {
        return self::query()->find($id)->update($order->all());
    }

    public static function remove($id)
    {
        return self::query()->find($id)->delete();
    }
}
