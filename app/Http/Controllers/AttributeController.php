<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttributeResource;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttributeController extends Controller
{
    /**
     * @return Response
     */
    public function index()
    {
        $attributes = Attribute::all();

        return AttributeResource::collection($attributes);
    }

    /**
     * @param int $id
     * @return Response
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
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:255',
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
     * @param Request $request
     * @param int $id
     * @return Response
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
                'name' => 'required|max:255',
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
     * @param int $id
     * @return Response
     */
    public function destroy(int $id)
    {
        $attribute = Attribute::find($id);

        if (empty($attribute)) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $attribute->delete();

        return response()->json([
            'message' => 'Attribute Deleted',
            'data' => new AttributeResource($attribute)
        ], 200);
    }
}
