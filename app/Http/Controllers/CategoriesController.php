<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Dotenv\Validator;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index()
    {
        return response()->json([
            'categories' => Categories::getAll(),
            'status' => true,
        ]);
    }

    public function show($id)
    {
        $category = Categories::getById($id);

        if(!$category) {
            return response()->json([
                'message' => 'Kategori tidak ditemukan!',
                'status' => false,
            ],400);
        }

        return response()->json([
            'category' => $category,
            'status' => true,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Categories::validate($request->all());

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()->first(),
                'status' => false,
            ], 400);
        }

        $category = Categories::insert($request);

        if(!$category){
            return response()->json([
                'message' => 'Kategori gagal ditambahkan!',
                'status' => false,
            ], 400);
        }

        return response()->json([
            'message' => 'Kategori berhasil ditambahkan!',
            'status' => true,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Categories::validate($request->all(), $id);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()->first(),
                'status' => false,
            ], 400);
        }

        $category = Categories::modify($id, $request);

        if(!$category){
            return response()->json([
                'message' => 'Kategori gagal diubah!',
                'status' => false,
            ], 400);
        }

        return response()->json([
            'message' => 'Kategori berhasil diubah!',
            'status' => true,
        ]);
    }

    public function destroy($id)
    {
        $category =  Categories::remove($id);

        if(!$category){
            return response()->json()([
                'message' => 'Kategori gagal dihapus!',
                'status' => false,
            ], 400);
        }

        return response()->json([
            'message' => 'Kategori berhasil dihapus!',
            'status' => true,
        ]);
    }
}
