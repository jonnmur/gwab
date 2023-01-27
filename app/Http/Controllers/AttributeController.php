<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttributeResource;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::all();

        return AttributeResource::collection($attributes);
    }

    public function show($id)
    {
        $attribute = Attribute::findOrFail($id);

        return new AttributeResource($attribute);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
            ],
        );

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()], 422);
        }

        $attribute = new Attribute();
        $attribute->name = $request->input('name');
        $attribute->save();

        return new AttributeResource($attribute);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
            ],
        );

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()], 422);
        }

        $attribute = Attribute::findOrFail($id);
        $attribute->name = $request->input('name');
        $attribute->save();
        
        return new AttributeResource($attribute);
    }

    public function destroy($id)
    {
        $attribute = Attribute::findOrFail($id);
        $attribute->delete();

        return new AttributeResource($attribute);
    }
}
