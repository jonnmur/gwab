<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Attribute;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');

            $products = Product::where('name', $search)
                ->orWhereHas('attributes', function ($query) use ($search) {
                    $query->where('name', $search);
                })->get();
        } 
        else {
            $products = Product::all();
        }

        return ProductResource::collection($products);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        return new ProductResource($product);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'price' => 'required',
            ],
        );

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()], 422);
        }

        $product = new Product();
        $product->name = $request->input('name');
        $product->price = $request->input('price');
        $product->save();

        if (!empty($request->input('attributes'))) {
            $attributes = Attribute::whereIn('id', $request->input('attributes'));
        }

        if (!empty($attributes)) {
            $product->attributes()->attach($attributes->pluck('id'));
        }

        return new ProductResource($product);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'price' => 'required',
            ],
        );

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()], 422);
        }

        $product = Product::findOrFail($id);
        $product->name = $request->input('name');
        $product->price = $request->input('price');
        $product->save();

        $attributes = [];

        if (!empty($request->input('attributes'))) {
            // Select only valid attributes
            $attributes = Attribute::whereIn('id', $request->input('attributes'));
        }

        if (!empty($attributes)) {
            // Update product attributes
            $product->attributes()->sync($attributes->pluck('id'));
        } 
        else {
            // Remove all attributes ()
            $product->attributes()->detach();
        }
        
        return new ProductResource($product);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->attributes()->detach();
        $product->delete();

        return new ProductResource($product);
    }
}
