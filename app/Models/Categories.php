<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public static function getCategories()
    {
        return self::query()->get();
    }
}
