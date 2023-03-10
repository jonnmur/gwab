<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttributeResource;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @group Attributes
 */
class AttributeController extends Controller
{
    /**
     * @response 200
     * {
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Tech"
     *     },
     *     {
     *       "id": 2,
     *       "name": "Home/Deco"
     *     }
     *   ]
     * }
     */
    public function index()
    {
        $attributes = Attribute::all();

        return AttributeResource::collection($attributes);
    }

    /**
     * @response 200
     * {
     *   "data": {
     *       "id": 1,
     *       "name": "Tech"
     *   }
     * }
     */
    public function show(int $id)
    {
        $attribute = Attribute::find($id);

        if (empty($attribute)) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        return new AttributeResource($attribute);
    }

    /**
     * @bodyParam name string required Attribute name
     * 
     * @response 201
     * {
     *   "message": "Attribute Created",
     *   "data": {
     *       "id": 1,
     *       "name": "Tech"
     *   }
     * }
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:attributes|max:255',
            ],
        );

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()], 422);
        }

        $attribute = new Attribute();
        $attribute->name = $request->input('name');
        $attribute->save();

        return response()->json([
            'message' => 'Attribute Created',
            'data' => new AttributeResource($attribute)
        ], 201);
    }

    /**
     * @bodyParam name string required Attribute name
     * 
     * @response 200
     * {
     *   "message": "Attribute Updated",
     *   "data": {
     *       "id": 1,
     *       "name": "Tech"
     *   }
     * }
     */
    public function update(Request $request, int $id)
    {
        $attribute = Attribute::find($id);

        if (empty($attribute)) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:255|unique:attributes,name,' . $id . ',id',
            ],
        );

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()], 422);
        }

        $attribute->name = $request->input('name');
        $attribute->save();

        return response()->json([
            'message' => 'Attribute Updated',
            'data' => new AttributeResource($attribute)
        ], 200);
    }

    /**
     * @bodyParam name string required Attribute name
     * 
     * @response 200
     * {
     *   "message": "Attribute Deleted",
     *   "data": {
     *       "id": 1,
     *       "name": "Tech"
     *   }
     * }
     */
    public function destroy(int $id)
    {
        $attribute = Attribute::find($id);

        if (empty($attribute)) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $attribute->products()->detach();
        $attribute->delete();

        return response()->json([
            'message' => 'Attribute Deleted',
            'data' => new AttributeResource($attribute)
        ], 200);
    }
}
