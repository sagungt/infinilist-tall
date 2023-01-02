<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::query()->get();
        return response([
            'status' => true,
            'message' => '',
            'data' => $tags
        ]);
    }

    public function show($id)
    {
        $tag = Tag::query()
            ->where('id', $id)
            ->first();

        if ($tag == null) {
            return response([
                'status' => false,
                'message' => 'Tag not found',
                'data' => null
            ]);
        }

        return response([
            'status' => true,
            'message' => '',
            'data' => $tag
        ]);
    }

    public function store(Request $request)
    {
        $payload = $request->all();

        $validator = Validator::make($payload, [
            'name' => 'required|alpha_num|min:2|max:20',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 422);
        }

        $tag = Tag::query()->create($payload);

        return response([
            'status' => true,
            'message' => '',
            'data' => $tag
        ]);
    }

    public function update(Request $request, $id)
    {
        $payload = $request->all();

        $validator = Validator::make($payload, [
            'name' => 'nullable|alpha_num|min:2|max:20',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 422);
        }

        $tag = Tag::query()->where('id', $id)->first();
        
        if ($tag == null) {
            return response([
                'status' => false,
                'message' => 'Tag not found',
                'data' => null,
            ], 404);
        }

        $tag->fill($payload);
        $tag->save();

        return response([
            'status' => true,
            'message' => '',
            'data' => $tag
        ]);
    }

    public function destroy($id)
    {
        $tag = Tag::query()->where('id', $id)->first();

        if ($tag == null) {
            return response([
                'status' => false,
                'message' => 'Tag not found',
                'data' => null,
            ], 404);
        }

        $tag->delete();

        return response([
            'status' => true,
            'message' => '',
            'data' => null,
        ]);
    }
}
