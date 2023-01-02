<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public static function getLikesByParentId($kind, $parent_id)
    {
        $likes = (new static)::query()
            ->where('kind', strtoupper($kind))
            ->where('parent_id', $parent_id)
            ->get();
        $users = User::getUsers();
        // $child = null;
        // if ($kind == 'post') {
        //     $child = Post::query()
        //         ->where('id', $parent_id)
        //         ->first();
        // }
        // if ($kind == 'comment') {
        //     $child = Comment::query()
        //         ->where('id', $parent_id)
        //         ->first();
        // }
        // if ($kind == 'chapter') {
        //     $child = [];
        // }

        $likes = $likes
            ->map(function ($like) use ($users) {
                $like['by'] = $users
                    ->where('id', $like->user_id)
                    ->first();
                return $like;
            });
        // $data = [
        //     'kind' => strtoupper($kind),
        //     'likes' => $likes,
        // ];
        // $data[$kind] = $child;
        return $likes;
    }

    public static function getLikesByOwner($user_id) {
        $likes = (new static)::query()
            ->where('user_id', $user_id)
            ->get();

            
        if ($likes) {
            $posts = Post::query()
                ->get();
            $comments = Comment::query()
                ->get();
            $series = Chapter::query()
                ->get();
            $likes = $likes->map(function ($like) use ($posts, $comments, $series) {
                if ($like['kind'] == 'POST') {
                    $like['child'] = $posts
                        ->where('id', $like->parent_id)
                        ->first();
                }
                if ($like['kind'] == 'COMMENT') {
                    $like['child'] = $comments
                        ->where('id', $like->parent_id)
                        ->first();
                }
                if ($like['kind'] == 'SERIES') {
                    $like['child'] = $series
                        ->where('id', $like->parent_id)
                        ->first();
                }
                return $like;
            });
        }

        return $likes;
    }
}
