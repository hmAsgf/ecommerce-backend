<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CartItems extends Model
{
    use HasFactory;

    protected $table = 'cart_items';
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public static function validate($data)
    {
        $rules = [
            'user_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
            'sub_total' => 'required',
        ];

        return Validator::make($data, $rules);
    }

    public static function getItemsByUserId($id)
    {
        return self::select('cart_items.id', 'products.id as product_id', 'products.name', 'products.image',
                            'products.price', 'cart_items.quantity', 'cart_items.sub_total')
                ->join('products', 'products.id', 'cart_items.product_id')
                ->where('user_id', $id)
                ->get();
    }

    public static function insert($item)
    {
        return self::updateOrInsert(
                ['user_id' => $item['user_id'], 'product_id' => $item['product_id']],
                ['quantity' => DB::raw('quantity + 1'), 'sub_total' => DB::raw("sub_total + {$item['sub_total']}")]);
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
