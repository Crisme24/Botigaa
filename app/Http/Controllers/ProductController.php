<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getAll(){
        try {
            //code...
            $products = Product::all();
            return $products;
        } catch (\Exception $e) {
            //throw $th;
            return response([
                'error'=> $e->getMessage()
            ], 500);
        }
    }

    public function insert(Request $request)
    {
        try {
            //code...
            //$categoriesIds = Category::all()->map(fn ($category) => $category->id)->toArray();
            $categoriesIds = Category::all()->map(function($category) {
                return $category->id;
            })->toArray();

            $body = $request->validate([
                'name' => 'required|string|max:40',
                'price' => 'required|numeric',
                'description' => 'string',
                'categories' => 'required|array|in:' . implode(',', $categoriesIds)
            ]);
            $body = $request->all();
            $product = Product::create($body);
            $product->categories()->attach($body['categories']);
            return response($product->load('categories'), 201);
        } catch (\Exception $e) {
            //throw $th;
            return response([
                'error'=> $e->getMessage(),
                'message'=> 'There was a problem to trying to created the product'
            ]);
        }
    }
}
