<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public static function getFavoritesByUserId($user_id)
    {
        $favorites = (new static)::query()->where('user_id', $user_id)->get();
        $posts = Post::getPosts();
        $favorites = collect($favorites)
            ->map(function ($favorite) use ($posts) {
                if ($favorite->kind == 'POST') {
                    $favorite['post'] = $posts
                        ->where('id', $favorite->parent_id)
                        ->first();
                }
                return $favorite;
            });
        return $favorites;
    }
}
