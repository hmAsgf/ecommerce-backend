<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
            'cart_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
            'sub_total' => 'required',
        ];

        return Validator::make($data, $rules);
    }

    public static function getAllByCartId($cartId)
    {
        return self::select('cart_items.id', 'products.id as product_id', 'products.name as product',
                            'products.price', 'cart_items.quantity', 'cart_items.sub_total')
                    ->join('products', 'products.id', 'cart_items.product_id')
                    ->where('cart_id', $cartId)
                    ->get();
    }

    public static function getByCartIdProductId($cartId, $productId)
    {
        return self::query()->where('cart_id', $cartId)
                    ->where('product_id', $productId)->get()->first();
    }

    public static function insert($item)
    {
        $hasItem = self::getByCartIdProductId($item->cart_id, $item->product_id);

        if($hasItem) {
            $item['quantity'] += $hasItem['quantity'];
            $item['sub_total'] += $hasItem['sub_total'];
            return self::query()->update($item->all());
        }

        return self::query()->create($item->all());
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
