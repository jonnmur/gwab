<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Attribute;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
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

    /**
     * @param int $id
     * @return Response
     */
    public function show(int $id)
    {
        $product = Product::find($id);

        if (empty($product)) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        return new ProductResource($product);
    }

    /**
     * @param Request $request
     * @return Response
     */
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

        $attributes = [];

        // Select only valid attributes
        if (!empty($request->input('attributes'))) {
            $attributes = Attribute::whereIn('id', $request->input('attributes'));
        }

        // Add product attributes (adds valid attributes only)
        if (!empty($attributes)) {
            $product->attributes()->attach($attributes->pluck('id'));
        }

        return response()->json([
            'message' => 'Product Created',
            'data' => new ProductResource($product)
        ], 201);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, int $id)
    {
        $product = Product::find($id);

        if (empty($product)) {
            return response()->json(['message' => 'Not Found'], 404);
        }

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

        $product->name = $request->input('name');
        $product->price = $request->input('price');
        $product->save();

        $attributes = [];

        // Select only valid attributes
        if (!empty($request->input('attributes'))) {
            $attributes = Attribute::whereIn('id', $request->input('attributes'));
        }

        // Update product attributes (syncs with valid attributes or removes all existing)
        if (!empty($attributes)) {
            $product->attributes()->sync($attributes->pluck('id'));
        } 
        else {
            $product->attributes()->detach();
        }

        return response()->json([
            'message' => 'Product Updated',
            'data' => new ProductResource($product)
        ], 200);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function destroy(int $id)
    {
        $product = Product::find($id);

        if (empty($product)) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $product->attributes()->detach();
        $product->delete();

        return response()->json([
            'message' => 'Product Deleted',
            'data' => new ProductResource($product)
        ], 200);
    }
}
