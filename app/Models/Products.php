<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public static function getProducts()
    {
        return self::select('products.id', 'categories.name as category', 'products.name', 'products.price')
                    ->join('categories', 'categories.id', 'products.category_id')
                    ->get();
    }

    public static function getProductById($id)
    {
        return self::select('products.id', 'categories.name as category', 'products.name', 'products.price')
                    ->join('categories', 'categories.id', 'products.category_id')
                    ->where('products.id', $id)
                    ->get()
                    ->first();
    }
}
