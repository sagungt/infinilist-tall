<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\MyList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ListController extends Controller
{
    private $item_kinds = ['post', 'series', 'text'];
    public function index()
    {
        $lists = MyList::getLists();

        return response([
            'status' => true,
            'message' => '',
            'data' => $lists,
        ]);
    }

    public function show($slug)
    {
        $list = MyList::getList($slug);

        if ($list == null) {
            return response([
                'status' => false,
                'message' => 'List not found',
                'data' => null,
            ], 404);
        }

        return response([
            'status' => true,
            'message' => '',
            'data' => $list,
        ]);
    }

    public function store(Request $request)
    {
        $payload = $request->all();

        $validator = Validator::make($payload, [
            'title' => 'required|min:2',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => 'Validation error',
                'data' => $validator->errors(),
            ], 422);
        }

        $payload['is_private'] = isset($payload['is_private']);

        if (isset($payload['series'])) {
            $series = Chapter::query()
                ->where('id', $payload['series'])
                ->first();

            if ($series == null) {
                return response([
                    'status' => false,
                    'message' => 'Series not found',
                    'data' => null,
                ]);
            }

            $payload['chapter_id'] = $payload['series'];
        }

        if (isset($payload['items'])) {
            $validator = Validator::make($payload, [
                'items' => 'required|array',
                'kind' => [
                    'required',
                    Rule::in($this->item_kinds),
                ]
            ]);
    
            if ($validator->fails()) {
                return response([
                    'status' => false,
                    'message' => 'Validation error',
                    'data' => $validator->errors(),
                ], 422);
            }
        }

        $payload['user_id'] = auth()->user()->id;
        $payload['slug'] = Str::slug($payload['title']) . '-' . Str::random(5);

        $list = MyList::query()
            ->create($payload);

        return response([
            'status' => true,
            'message' => '',
            'data' => $list,
        ]);
    }

    public function update(Request $request, $slug)
    {
        $payload = $request->all();

        $validator = Validator::make($payload, [
            ''
        ]);
    }

    public function destroy($slug)
    {
        $list = MyList::query()
            ->where('slug', $slug)
            ->first();

        if ($list == null) {
            return response([
                'status' => false,
                'message' => 'List not found',
                'data' => null,
            ]);
        }

        $list->delete();

        return response([
            'status' => true,
            'message' => '',
            'data' => null,
        ]);
    }
}
