<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    private $kinds = ['post', 'comment', 'series'];
    private $type = 'api';
    public function index($kind, $parent_id)
    {
        if (!in_array($kind, $this->kinds)) {
            return response([
                'status' => false,
                'message' => 'Invalid kind',
                'data' => null,
            ]);
        }

        $child = null;
        if ($kind == 'post') {
            $child = Post::query()->where('id', $parent_id)->first();
            if ($child == null) {
                return response([
                    'status' => false,
                    'message' => 'Post not found',
                    'data' => null,
                ]);
            }
        }

        if ($kind == 'comment') {
            $child = Comment::query()->where('id', $parent_id)->first();
            if ($child == null) {
                return response([
                    'status' => false,
                    'message' => 'Comment not found',
                    'data' => null,
                ]);
            }
        }

        if ($kind == 'series') {
            $child = Chapter::query()->where('id', $parent_id)->first();
            if ($child == null) {
                return response([
                    'status' => false,
                    'message' => 'Series not found',
                    'data' => null,
                ]);
            }
        }

        $likes = Like::getLikesByParentId($kind, $parent_id);
        return response([
            'status' => true,
            'message' => '',
            'data' => $likes,
        ]);
    }

    public function getLikeByOwner()
    {
        $user_id = auth()->user()->id;
        $likes = Like::query()
            ->where('user_id', $user_id)
            ->get();

        $posts = Post::query()
            ->get();
        $comments = Comment::query()
            ->get();
        $series = Chapter::query()
            ->get();

        if ($likes) {
            $likes = $likes->map(function ($like) use ($posts, $comments, $series) {
                $like['post'] = $posts
                    ->where('id', $like->parent_id)
                    ->first();
                $like['comment'] = $comments
                    ->where('id', $like->parent_id)
                    ->first();
                $like['series'] = $series
                    ->where('id', $like->parent_id)
                    ->first();
                return $like;
            });
        }
        return response([
            'status' => true,
            'message' => '',
            'data' => $likes
        ]);
    }

    public function toggleLike($kind, $parent_id)
    {
        $user_id = auth()->user()->id;
        if (!in_array($kind, $this->kinds)) {
            return response([
                'status' => false,
                'message' => 'Invalid kind',
                'data' => null,
            ]);
        }

        $child = null;
        if ($kind == 'post') {
            $child = Post::query()->where('id', $parent_id)->first();
            if ($child == null) {
                return response([
                    'status' => false,
                    'message' => 'Post not found',
                    'data' => null,
                ]);
            }
        }

        if ($kind == 'comment') {
            $child = Comment::query()->where('id', $parent_id)->first();
            if ($child == null) {
                return response([
                    'status' => false,
                    'message' => 'Comment not found',
                    'data' => null,
                ]);
            }
        }

        if ($kind == 'series') {
            $child = Chapter::query()->where('id', $parent_id)->first();
            if ($child == null) {
                return response([
                    'status' => false,
                    'message' => 'Series not found',
                    'data' => null,
                ]);
            }
        }

        $like = Like::query()
            ->where('kind', strtoupper($kind))
            ->where('parent_id', $parent_id)
            ->where('user_id', $user_id)
            ->first();
        
        if ($like == null) {
            $like = Like::query()
                ->create([
                    'kind' => strtoupper($kind),
                    'parent_id' => $parent_id,
                    'user_id' => $user_id,
                ]);
            if ($this->type == 'web') {
                return redirect()->back()->with([
                    'success' => ucfirst($kind) . ' liked'
                ]);
            }
            return response([
                'status' => true,
                'message' => 'Liked',
                'data' => $like,
            ]);
        }

        $like->delete();

        if ($this->type == 'web') {
            return redirect()->back()->with([
                'success' => ucfirst($kind) . ' unliked'
            ]);
        }
        return response([
            'status' => true,
            'message' => 'Unliked',
            'data' => null,
        ]);
    }
}
