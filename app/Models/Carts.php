<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carts extends Model
{
    use HasFactory;

    protected $table = 'carts';
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public static function getByUserId($id)
    {
        return self::query()->where('user_id', $id)->get()->first();
    }

    public static function insert($cart)
    {
        return self::query()->create($cart->all());
    }
}
