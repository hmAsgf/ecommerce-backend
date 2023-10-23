<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index()
    {
        return response()->json([
            'categories' => Categories::getCategories(),
            'status' => true,
        ]);
    }
}
