<?php

namespace App\Http\Controllers;

use App\Models\ItemCategory;
use App\Models\Attachment;
use App\Models\Shortener;
use App\Models\Favorite;
use App\Models\Category;
use App\Models\ItemTag;
use App\Models\Chapter;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    private $type = 'web';
    private $kind = 'POST';
    public function index()
    {
        $posts = Post::getPosts()
            ->where('is_published', true)
            ->sortByDesc('created_at')
            ->values();
            
        return response([
            'status' => true,
            'message' => '',
            'data' => $posts,
        ], 200);
    }

    public function postsByOwner()
    {
        $user_id = auth()->user()->id;
        $posts = Post::getPosts()
            // ->where('chapter_id', 0)
            ->where('user_id', $user_id)
            ->values();

        return response([
            'status' => true,
            'message' => '',
            'data' => $posts,
        ], 200);
    }

    public function allPostsByOwner()
    {
        $user_id = auth()->user()->id;
        $posts = Post::getPosts()
            ->where('user_id', $user_id)
            ->values();

        return response([
            'status' => true,
            'message' => '',
            'data' => $posts,
        ], 200);
    }

    public function store(Request $request)
    {
        $payload = $request->all();

        $category_ids = Category::query()
            ->get('id')
            ->map(fn ($category) => $category['id'])
            ->toArray();

        $tag_ids = Tag::query()
            ->get('id')
            ->map(fn ($tag) => $tag['id'])
            ->toArray();

        $validator = Validator::make($payload, [
            'title' => 'required|max:100',
            'content' => 'required',
            'cover' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'categories' => [
                'required',
                'array',
                Rule::in($category_ids)
            ],
            'tags' => [
                'required',
                'array',
                Rule::in($tag_ids)
            ]
        ]);

        $payload['is_published'] = isset($payload['is_published']) ? true : false;

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

        if (isset($payload['series'])) {
            $chapter = Chapter::query()
                ->where('id', $payload['series'])
                ->first();

            if ($chapter == null) {
                if ($this->type == 'web') {
                    return redirect()->back()->withErrors([
                        'series' => 'Series not found'
                    ]);
                }
                return response([
                    'status' => false,
                    'message' => 'Series not found',
                    'data' => null,
                ]);
            }

            $payload['chapter_id'] = $chapter->id;
            $last_post_chapter = Post::query()
                ->where('chapter_id', $chapter->id)
                ->latest()
                ->first();

            if ($last_post_chapter != null) {
                $payload['chapter_order'] = $last_post_chapter->chapter_order + 1;
            } else {
                $payload['chapter_order'] = 1;
            }
        }

        $cover = $request->file('cover');
        $filename = $cover->hashName();
        $cover->storeAs('cover', $filename);
        $path = $request->getSchemeAndHttpHost() . "/storage/cover/" . $filename;

        $payload['slug'] = Str::slug($payload['title']) . '-' . Str::random(5);

        $user_id = auth()->user()->id;
        $payload['user_id'] = $user_id;

        $post = Post::query()->create($payload);

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
                'user_id' => $user_id,
                'kind' => $this->kind,
                'parent_id' => $post->id,
                'alias' => $payload['url'],
                'target' => $request->getSchemeAndHttpHost() . '/posts/' . $post->slug,
            ]);

            $post['shortener'] = $shorten;
        }

        Attachment::query()->create([
            'kind' => strtoupper($this->kind),
            'parent_id' => $post->id,
            'path' => $path,
        ]);

        foreach($payload['categories'] as $category) {
            ItemCategory::query()->create([
                'parent_id' => $post->id,
                'category_id' => $category,
                'kind' => strtoupper($this->kind),
            ]);
        }
        
        foreach($payload['tags'] as $tag) {
            ItemTag::query()->create([
                'parent_id' => $post->id,
                'tag_id' => $tag,
                'kind' => strtoupper($this->kind),
            ]);
        }

        $post['categories'] = Category::query()
            ->get(['id', 'name'])
            ->whereIn('id', $payload['categories'])
            ->all();
        $post['tags'] = Tag::query()
            ->get(['id', 'name'])
            ->whereIn('id', $payload['tags'])
            ->all();

        if ($this->type == 'web') {
            return redirect()->back()->with([
                'success' => 'Post created'
            ]);
        }
        return response([
            'status' => true,
            'message' => '',
            'data' => $post,
        ]);
    }

    public function show($slug)
    {
        $post = Post::getPost($slug);

        if ($post == null) {
            return response([
                'status' => false,
                'message' => 'Post not found',
                'data' => null,
            ], 404);
        }

        $last_view_post = Post::query()
            ->where('slug', $slug)
            ->first();
        $last_view_post->view_count += 1;
        $last_view_post->save();
        
        $categories = Category::query()->get();
        $tags = Tag::query()->get();
        
        $post_categories = ItemCategory::query()
            ->where('kind', 'POST')
            ->where('parent_id', $post->id)
            ->get()
            ->map(fn ($category) => $category['category_id'])
            ->toArray();
        $post_tags = ItemTag::query()
            ->where('kind', 'POST')
            ->where('parent_id', $post->id)
            ->get()
            ->map(fn ($tag) => $tag['tag_id'])
            ->toArray();
        $post_favorites = Favorite::query()
            ->where('kind', $this->kind)
            ->where('parent_id', $post->id)
            ->get();
        
        $post['categories'] = $categories
            ->whereIn('id', $post_categories)
            ->values();
        $post['tags'] = $tags
            ->whereIn('id', $post_tags)
            ->values();
        $post['favorites'] = $post_favorites;

        return response([
            'status' => true,
            'message' => '',
            'data' => $post,
        ]);
    }

    public function update(Request $request, $slug)
    {
        $post = Post::query()
            ->where('slug', $slug)
            ->first();

        if ($post == null) {
            if ($this->type == 'web') {
                return redirect()->back()->withErrors([
                    'error' => 'Post not found'
                ]);
            }
            return response([
                'status' => false,
                'message' => 'Post not found',
                'data' => null,
            ], 404);
        }

        $payload = $request->all();

        $validator = Validator::make($payload, [
            'title' => 'required|max:100',
            'content' => 'required',
        ]);

        $payload['is_published'] = isset($payload['is_published']) ? true : false;

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

        if (isset($payload['categories'])) {
            $category_ids = Category::query()
                ->get('id')
                ->map(fn ($category) => $category['id'])
                ->toArray();

            // $allowed_category_ids = Post::query()
            //     ->whereNotIn('id', $category_ids)
            //     ->get('id')
            //     ->map(fn ($category) => $category['id'])
            //     ->toArray();

            $validator = Validator::make($payload, [
                'categories' => [
                    'required',
                    'array',
                    Rule::in($category_ids)
                ],
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

            ItemCategory::where('parent_id', $post->id)
                ->where('kind', $this->kind)
                ->delete();

            foreach($payload['categories'] as $category) {
                ItemCategory::query()->create([
                    'parent_id' => $post->id,
                    'category_id' => $category,
                    'kind' => $this->kind,
                ]);
            }
        }

        if (isset($payload['tags'])) {
            $tag_ids = Tag::query()
                ->get('id')
                ->map(fn ($tag) => $tag['id'])
                ->toArray();

            // $allowed_tag_ids = Post::query()
            //     ->whereNotIn('id', $tag_ids)
            //     ->get('id')
            //     ->map(fn ($tag) => $tag['id'])
            //     ->toArray();

            $validator = Validator::make($payload, [
                'tags' => [
                    'required',
                    'array',
                    Rule::in($tag_ids)
                ]
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

            ItemTag::where('parent_id', $post->id)
                ->where('kind', $this->kind)
                ->delete();

            foreach($payload['tags'] as $tag) {
                ItemTag::query()->create([
                    'parent_id' => $post->id,
                    'tag_id' => $tag,
                    'kind' => $this->kind,
                ]);
            }
        }

        if (isset($payload['cover'])) {
            $validator = Validator::make($payload, [
                'cover' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
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

        if ($request->hasFile('cover')) {
            $cover = $request->file('cover');
            $filename = $cover->hashName();
            $cover->storeAs('cover', $filename);
            $path = $request->getSchemeAndHttpHost() . "/storage/cover/" . $filename;

            $attachment = Attachment::query()
                ->where('kind', $this->kind)
                ->where('parent_id', $post->id)
                ->first();

            $previous_file = str_replace($request->getSchemeAndHttpHost() . '/storage', '', $attachment->path);
            Storage::delete($previous_file);

            $attachment->fill([
                'path' => $path,
            ]);
            $attachment->save();
        }

        $post->fill($payload);
        $post->save();

        if ($this->type == 'web') {
            return redirect()->back()->with([
                'success' => 'Post updated'
            ]);
        }
        
        return response([
            'status' => true,
            'message' => '',
            'data' => $post,
        ]);
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $upload = $request->file('upload');
            $filename = $upload->hashName();
            $upload->storeAs('upload', $filename);
            $path = $request->getSchemeAndHttpHost() . "/storage/upload/" . $filename;

            return response()->json(['fileName' => $filename, 'uploaded'=> 1, 'url' => $path]);
        }
    }

    public function destroy($slug)
    {
        $post = Post::getPost($slug);

        if ($post == null) {
            if ($this->type == 'web') {
                return redirect()->back()->withErrors([
                    'error' => 'Post not found'
                ]);
            }
            return response([
                'status' => false,
                'message' => 'Post not found',
                'data' => null,
            ], 404);
        }

        $post->delete();

        if ($this->type == 'web') {
            return redirect()->back()->with([
                'success' => 'Post deleted'
            ]);
        }
        return response([
            'status' => true,
            'message' => '',
            'data' => null,
        ]);
    }

    public function removeFromChapter(Request $request, $slug)
    {
        $post = Post::getPost($slug);

        if ($post == null) {
            return response([
                'status' => false,
                'message' => 'Post not found',
                'data' => null,
            ], 404);
        }

        $payload = $request->all();

        $posts = Post::query()
            ->where('chapter_id', $payload['chapter_id'])
            ->get();

        foreach($posts as $index => $p) {
            $p->fill([
                    'chapter_order' => $index + 1,
                ]);
            $p->save();
        }

        $post->fill([
            'chapter_id' => 0,
            'chapter_order' => 0
        ]);
        $post->save();

        return response([
            'status' => true,
            'message' => '',
            'data' => $post,
        ]);
    }
}
