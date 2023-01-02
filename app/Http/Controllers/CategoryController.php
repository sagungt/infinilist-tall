<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::query()->get();
        return response([
            'status' => true,
            'message' => '',
            'data' => $categories
        ]);
    }

    public function show($id)
    {
        $category = Category::query()
            ->where('id', $id)
            ->first();

        if ($category == null) {
            return response([
                'status' => false,
                'message' => 'Category not found',
                'data' => null
            ]);
        }

        return response([
            'status' => true,
            'message' => '',
            'data' => $category
        ]);
    }

    public function store(Request $request)
    {
        $payload = $request->all();

        $validator = Validator::make($payload, [
            'name' => 'required|min:2|max:20',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 422);
        }

        $category = Category::query()->create($payload);

        return response([
            'status' => true,
            'message' => '',
            'data' => $category
        ]);
    }

    public function update(Request $request, $id)
    {
        $payload = $request->all();

        $validator = Validator::make($payload, [
            'name' => 'nullable|min:2|max:20',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 422);
        }

        $category = Category::query()->where('id', $id)->first();
        
        if ($category == null) {
            return response([
                'status' => false,
                'message' => 'Category not found',
                'data' => null,
            ], 404);
        }

        $category->fill($payload);
        $category->save();

        return response([
            'status' => true,
            'message' => '',
            'data' => $category
        ]);
    }

    public function destroy($id)
    {
        $category = Category::query()->where('id', $id)->first();

        if ($category == null) {
            return response([
                'status' => false,
                'message' => 'Category not found',
                'data' => null,
            ], 404);
        }

        $category->delete();

        return response([
            'status' => true,
            'message' => '',
            'data' => null,
        ]);
    }
}
