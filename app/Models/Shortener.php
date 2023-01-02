<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shortener extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public static function getShortenersByOwner($user_id)
    {
        $shorteners = (new static)::query()
            ->where('user_id', $user_id)
            ->get();

        $users = User::getUsers();
        $posts = Post::getPosts();
        $chapters = Chapter::query()->get();

        $shorteners = $shorteners
            ->map(function ($shortener) use ($users, $posts, $chapters) {
                $shortener['owner'] = $users
                    ->where('id', $shortener->id)
                    ->first();

                if ($shortener['kind'] == 'POST') {
                    $shortener['post'] = $posts
                        ->where('id', $shortener->parent_id)
                        ->first();
                }
                if ($shortener['kind'] == 'CHAPTER') {
                    $shortener['chapter'] = $chapters
                        ->where('id', $shortener->parent_id)
                        ->first();
                }
                return $shortener;
            });
        return $shorteners->whereNull('deleted_at');
    }
}
