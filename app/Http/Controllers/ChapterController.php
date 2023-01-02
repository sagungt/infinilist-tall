<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Post;
use App\Models\Shortener;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ChapterController extends Controller
{
    private $kinds = ['post'];
    private $kind = 'SERIES';
    private $type = 'web';
    public function index()
    {
        $chapters = Chapter::getChapters();

        return response([
            'status' => true,
            'message' => '',
            'data' => $chapters,
        ]);
    }

    public function show($slug)
    {
        $chapter = Chapter::getChapter($slug);

        if ($chapter == null) {
            return response([
                'status' => false,
                'message' => 'Series not found',
                'data' => null,
            ]);
        }

        return response([
            'status' => true,
            'message' => '',
            'data' => $chapter,
        ]);
    }

    public function chaptersByOwner()
    {
        $user_id = auth()->user()->id;
        $chapters = Chapter::getChapters()
            ->where('user_id', $user_id)
            ->all();

        return response([
            'status' => true,
            'message' => '',
            'data' => $chapters,
        ]);
    }

    public function store(Request $request, $kind)
    {
        $user_id = auth()->user()->id;
        if (!in_array($kind, $this->kinds)) {
            if ($this->type == 'web') {
                return redirect()->back()->withErrors([
                    'error' => 'Invalid kind',
                ]);
            }
            return response([
                'status' => false,
                'message' => 'Invalid kind',
                'data' => null,
            ], 400);
        }
        $payload = $request->all();

        $validator = Validator::make($payload, [
            'name' => 'required|min:2|max:100',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            if ($this->type == 'web') {
                return redirect()->back()->withErrors($validator->errors());
            }
            return response([
                'status' => false,
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 422);
        }

        if (isset($payload['posts'])) {
            $post_ids = Post::query()
                ->get('id')
                ->map(fn ($post) => $post['id'])
                ->toArray();

            $validator = Validator::make($payload, [
                'posts' => [
                    'required',
                    'array',
                    Rule::in($post_ids),
                ],
                'posts.*' => 'distinct',
            ]);

            if ($validator->fails()) {
                if ($this->type == 'web') {
                    return redirect()->back()->withErrors($validator->errors());
                }
                return response([
                    'status' => false,
                    'message' => 'Validation error',
                    'data' => $validator->errors()
                ], 422);
            }
        }

        $payload['slug'] = Str::slug($payload['name']) . '-' . Str::random(5);
        $payload['kind'] = strtoupper($kind);
        $payload['user_id'] = $user_id;

        $chapter = Chapter::query()
            ->create($payload);

        if (isset($payload['url'])) {
            $validator = Validator::make($payload, [
                'url' => 'min:3|unique:shorteners,alias',
            ]);

            if ($validator->fails()) {
                if ($this->type == 'web') {
                    return redirect()->back()->withErrors($validator->errors());
                }
            }
            $shorten = Shortener::query()->create([
                'kind' => $this->kind,
                'parent_id' => $chapter->id,
                'alias' => $payload['url'],
                'target' => $request->getSchemeAndHttpHost() . '/posts/' . $payload['slug'],
            ]);

            $post['shortener'] = $shorten;
        }

        if (isset($payload['posts'])) {
            $posts = Post::query()
                ->get()
                ->whereIn('id', $payload['posts']);
    
            foreach ($payload['posts'] as $index => $id) {
                $post = $posts
                    ->where('id', $id)
                    ->first();
                $post->fill([
                    'chapter_id' => $chapter->id,
                    'chapter_order' => $index + 1,
                ]);
                $post->save();
            }
    
            $chapter['posts'] = Post::getPosts()
                ->where('chapter_id', $chapter->id)
                ->sortBy([
                    ['chapter_order', 'ASC']
                ])
                ->values();
        }

        if ($this->type == 'web') {
            return redirect()->back()->with([
                'success' => 'Series created',
            ]);
        }
        
        return response([
            'status' => true,
            'message' => '',
            'data' => $chapter,
        ]);
    }

    public function update(Request $request, $kind, $slug)
    {
        if (!in_array($kind, $this->kinds)) {
            if ($this->type == 'web') {
                return redirect()->back()->withErrors([
                    'error' => 'Invalid kind',
                ]);
            }
            return response([
                'status' => false,
                'message' => 'Invalid kind',
                'data' => null,
            ], 400);
        }
        
        $payload = $request->all();

        $chapter = Chapter::query()
            ->where('slug', $slug)
            ->first();

        if ($chapter == null) {
            if ($this->type == 'web') {
                return redirect()->back()->withErrors([
                    'error' => 'Chapter not found',
                ]);
            }
            return response([
                'status' => false,
                'message' => 'Chapter not found',
                'data' => null,
            ], 404);
        }

        $validator = Validator::make($payload, [
            'name' => 'required|min:2|max:100',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            if ($this->type == 'web') {
                return redirect()->back()->withErrors($validator->errors());
            }
            return response([
                'status' => false,
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 422);
        }

        
        if (isset($payload['posts'])) {
            $post_ids = Post::query()
                // ->where('chapter_id', $chapter->id)
                ->get('id')
                ->map(fn ($post) => $post['id'])
                ->toArray();

            // $allowed_post_ids = Post::query()
            //     ->whereNotIn('id', $post_ids)
            //     ->get('id')
            //     ->map(fn ($post) => $post['id'])
            //     ->toArray();

            $validator = Validator::make($payload, [
                'posts' => [
                    'required',
                    'array',
                    Rule::in($post_ids),
                ],
                'posts.*' => 'distinct',
            ]);
    
            if ($validator->fails()) {
                if ($this->type == 'web') {
                    return redirect()->back()->withErrors($validator->errors());
                }
                return response([
                    'status' => false,
                    'message' => 'Validation error',
                    'data' => $validator->errors()
                ], 422);
            }

            $posts = Post::query()
                ->get()
                ->whereIn('id', $payload['posts']);
            $last_chapter_order = Chapter::query()
                ->where('id', $chapter->id)
                ->latest()
                ->first();
                
            foreach ($payload['posts'] as $index => $id) {
                $post = $posts
                    ->where('id', $id)
                    ->first();
                $post->fill([
                    'chapter_id' => $chapter->id,
                    'chapter_order' => $index + 1 + $last_chapter_order->chapter_order,
                ]);
                $post->save();
            }
        }

        $chapter->fill([
            'name' => $payload['name'],
            'description' => $payload['description'],
        ]);
        $chapter->save();

        if ($this->type == 'web') {
            return redirect()->back()->with([
                'success' => 'Series updated',
            ]);
        }

        return response([
            'status' => true,
            'message' => '',
            'data' => $chapter,
        ]);
    }

    public function destroy($kind, $slug)
    {
        if (!in_array($kind, $this->kinds)) {
            if ($this->type == 'web') {
                return redirect()->back()->withErrors([
                    'error' => 'Invalid kind',
                ]);
            }
            return response([
                'status' => false,
                'message' => 'Invalid kind',
                'data' => null,
            ], 400);
        }
        $chapter = Chapter::getChapter($slug);

        if ($chapter == null) {
            if ($this->type == 'web') {
                return redirect()->back()->withErrors([
                    'error' => 'Chapter not found',
                ]);
            }
            return response([
                'status' => false,
                'message' => 'Series not found',
                'data' => null,
            ], 404);
        }

        $posts = Post::query()
            ->where('chapter_id', 1)
            ->get();

        foreach ($posts as $post) {
            $post->fill([
                'chapter_id' => 0,
                'chapter_order' => 0,
            ]);
            $post->save();
        }

        $chapter->delete();

        if ($this->type == 'web') {
            return redirect()->back()->with([
                'success' => 'Series deleted',
            ]);
        }

        return response([
            'status' => true,
            'message' => '',
            'data' => null,
        ]);
    }
}
