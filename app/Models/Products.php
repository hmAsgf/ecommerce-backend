<?php

namespace App\Models;

use App\Http\Controllers\ImageController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Products extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    private static $folderPath = 'public/products/';

    public static function validate($data, $id=null)
    {
        $rules = [
            'name' => "required|unique:products,name,$id",
            'category_id' => 'required',
            'price' => 'required',
        ];

        if($data['image']){
            $rules['image'] = 'required';
        }

        return Validator::make($data, $rules);
    }

    public static function getAll()
    {
        return self::select('products.id', 'categories.name as category', 'products.name', 'products.price', 'products.image')
                    ->join('categories', 'categories.id', 'products.category_id')
                    ->get();
    }

    public static function getById($id)
    {
        return self::select('products.id', 'categories.name as category', 'products.name', 'products.price')
                    ->join('categories', 'categories.id', 'products.category_id')
                    ->where('products.id', $id)
                    ->get()
                    ->first();
    }

    public static function insert($product)
    {
        $image = new ImageController($product->image);

        $product['image'] = $image->getRandomName('product');
        $imagePath = self::$folderPath . $product['image'];
        $image->store($imagePath);

        return self::query()->create($product->all());
    }

    public static function modify($id, $product)
    {
        if($product->image){
            $image = new ImageController($product->image);
            // Menyimpan gambar baru
            $product['image'] = $image->getRandomName('product');
            $imagePath = self::$folderPath . $product['image'];
            $image->store($imagePath);
            // Menghapus gambar lama
            $oldImage = self::query()->find($id);
            if(!$oldImage){
                return $oldImage;
            }
            $oldImagePath = self::$folderPath . $oldImage;

            if($image->fileExists($oldImagePath)){
                $image->delete($oldImagePath);
            }
        }else{
            $product['image'] = self::query()->find($id)->image;
        }

        return self::query()->find($id)
                    ->update($product->all());
    }

    public static function remove($id)
    {
        $image = new ImageController();
        // Menghapus gambar lama
        $oldImage = self::query()->find($id);
        if(!$oldImage){
            return $oldImage;
        }
        $oldImagePath = self::$folderPath . $oldImage->image;

        if($image->fileExists($oldImagePath)){
            $image->delete($oldImagePath);
        }

        return self::query()->find($id)->delete();
    }
}
