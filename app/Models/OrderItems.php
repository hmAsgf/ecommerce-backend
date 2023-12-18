<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class OrderItems extends Model
{
    use HasFactory;

    protected $table = 'order_items';
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public static function validate($data)
    {
        $rules = [
            'product_id' => 'required',
            'quantity' => 'required',
            'sub_total' => 'required',
        ];

        return Validator::make($data, $rules);
    }

    public static function getByOrderId($orderId)
    {
        return self::select('order_items.id', 'products.id as product_id', 'products.name',
                            'order_items.quantity', 'order_items.sub_total')
                ->join('products', 'products.id', 'order_items.product_id')
                ->where('order_id', $orderId)
                ->get();
    }

    public static function insert($items, $orderId)
    {
        foreach($items as $item) {
            $item['order_id'] = $orderId;
            self::query()->create($item);
        }
    }

    public static function modify($id, $item)
    {
        return self::query()->find($id)->update($item->all());
    }

    public static function remove($id)
    {
        return self::query()->find($id)->delete();
    }
}
