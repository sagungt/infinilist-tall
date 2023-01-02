<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    
    public static function getChapter($slug)
    {
        $chapter = (new static)::query()
            ->where('slug', $slug)
            ->first();
        
        if ($chapter) {
            $posts = Post::query()
                ->where('chapter_id', $chapter->id)
                ->get();
            $user = User::getUser($chapter->user_id);
            $chapter['posts'] = $posts;
            $chapter['owner'] = $user;
        }
        
        return $chapter;
    }

    public static function getChapters()
    {
        $chapters = (new static)::query()->get();
        $posts = Post::getPosts();
        $users = User::getUsers();
        $likes = Like::query()
            ->get();
        $comments = Comment::query()
            ->get();
        $favorites = Favorite::query()
            ->get();
        $chapters = $chapters
            ->map(function ($chapter) use ($posts, $users, $likes, $comments, $favorites) {
                $post = $posts
                    ->where('chapter_id', $chapter->id)
                    ->first();

                $user = $users
                    ->where('id', $chapter->user_id)
                    ->first();

                $chapter['likes'] = $likes
                    ->where('kind', 'SERIES')
                    ->where('parent_id', $chapter->id)
                    ->values();

                $chapter['comments'] = $comments
                    ->where('kind', 'SERIES')
                    ->where('parent_id', $chapter->id);

                $chapter['favorites'] = $favorites
                    ->where('kind', 'SERIES')
                    ->where('parent_id', $chapter->id);

                $chapter['posts'] = $post;
                $chapter['owner'] = $user;
                return $chapter;
            });

        return $chapters;
    }
}
