<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Categories extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    private static $folderpath = 'public/categories/';

    public static function getAll()
    {
        
        return self::select('categories.id', 'categories.name as category')
            ->selectRaw('COUNT(products.id) as item_count')
            ->Leftjoin('products', 'products.category_id', 'categories.id')
            ->groupBy('categories.id')
            ->get();
    }

    public static function getById($id)
    {
        return self::query()->where('id',$id)->get()->first();
    }

    public static function validate($data, $id=null)
    {
        $rules = [
            'name' => "required|unique:categories,name,$id"
        ];

        return Validator::make($data, $rules);
    }

    public static function insert($categories)
    {
        return self::query()->create($categories->all());
    }

    public static function modify($id, $categories)
    {
        return self::query()->find($id)
                            ->update($categories->all());
    }

    public static function remove($id)
    {
        return self::query()->find($id)->delete();
    }
}
