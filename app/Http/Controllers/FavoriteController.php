<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Chapter;
use App\Models\Post;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    private $kinds = ['post', 'series'];
    private $type = 'web';
    public function index()
    {
        $user_id = auth()->user()->id;
        $favorites = Favorite::getFavoritesByUserId($user_id);
        return response([
            'status' => true,
            'message' => '',
            'data' => $favorites,
        ]);
    }

    public function favoritesByParent($kind, $parent_id) {
        $favorites = Favorite::query()
            ->where('kind', strtoupper($kind))
            ->where('parent_id', $parent_id)
            ->get();

        return response([
            'status' => true,
            'message' => '',
            'data' => $favorites,
        ]);
    }

    public function toggleFavorite($kind, $parent_id)
    {
        $user_id = auth()->user()->id;
        if (!in_array($kind, $this->kinds)) {
            return response([
                'status' => false,
                'message' => 'Invalid kind',
                'data' => null,
            ], 400);
        }

        $child = null;
        if ($kind == 'post') {
            $child = Post::query()->where('id', $parent_id)->first();
            if ($child == null) {
                return response([
                    'status' => false,
                    'message' => 'Post not found',
                    'data' => null,
                ], 404);
            }
        }

        if ($kind == 'series') {
            $child = Chapter::query()->where('id', $parent_id)->first();
            if ($child == null) {
                return response([
                    'status' => false,
                    'message' => 'Series not found',
                    'data' => null,
                ], 404);
            }
        }

        $favorited = Favorite::query()
            ->where('kind', strtoupper($kind))
            ->where('parent_id', $parent_id)
            ->where('user_id', $user_id)
            ->first();

        if ($favorited != null) {
            $favorited->delete();

            return response([
                'status' => true,
                'message' => 'Removed from favorite',
                'data' => null,
            ]);
        }

        $favorites = Favorite::query()->create([
            'kind' => strtoupper($kind),
            'parent_id' => $parent_id,
            'user_id' => $user_id,
        ]);

        $favorites[$kind] = $child;

        return response([
            'status' => true,
            'message' => 'Added to favorite',
            'data' => $favorites,
        ]);
    }
}
