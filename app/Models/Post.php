<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public static function getPost($slug) {
        $post = (new static)::where('slug', $slug)->first();
        if ($post) {
            $categories = Category::query()
                ->get();
            $tags = Tag::query()
                ->get();
            $post_categories = ItemCategory::query()
                ->get()
                ->where('kind', 'POST')
                ->where('parent_id', $post->id)
                ->map(fn ($category) => $category['category_id'])
                ->toArray();;
            $post_tags = ItemTag::query()
                ->get()
                ->where('kind', 'POST')
                ->where('parent_id', $post->id)
                ->map(fn ($tag) => $tag['tag_id'])
                ->toArray();
            $post['owner'] = User::getUser($post->user_id);
            $post['cover'] = Attachment::query()
                ->where('kind', 'POST')
                ->where('parent_id', $post->id)
                ->first();
            $post['categories'] = $categories
                ->whereIn('id', $post_categories)
                ->values();
            $post['tags'] = $tags
                ->whereIn('id', $post_tags)
                ->values();
            
            if (intval($post['chapter_id']) > 0) {
                $post['series'] = Chapter::query()
                    ->where('kind', 'POST')
                    ->where('id', $post['chapter_id'])
                    ->first();
            } else {
                $post['series'] = [];
            }
        }
        return $post;
    }

    public static function getPosts() {
        $posts = (new static)::where('deleted_at', null)->get();
        $attachments = Attachment::query()
            ->where('kind', 'POST');
        $users = User::getUsers();
        $chapters = Chapter::query()->get();
        $users = User::getUsers();
        $posts_categories = ItemCategory::query()
            ->get()
            ->where('kind', 'POST');
        $posts_tags = ItemTag::query()
            ->get()
            ->where('kind', 'POST');
        $categories = Category::query()
            ->get();
        $tags = Tag::query()
            ->get();
        $likes = Like::query()
            ->get();
        $comments = Comment::query()
            ->get();
        $favorites = Favorite::query()
            ->get();
        $posts = collect($posts)
            ->map(function ($post) use (
                $users, $posts_categories, $posts_tags, $categories, $tags, $likes, $comments, $favorites, $chapters, $attachments
            ) {
                $post_categories = $posts_categories
                    ->where('parent_id', $post->id)
                    ->map(fn ($category) => $category['category_id'])
                    ->toArray();
                $post_tags = $posts_tags
                    ->where('parent_id', $post->id)
                    ->map(fn ($tag) => $tag['tag_id'])
                    ->toArray();
                $post['categories'] = $categories
                    ->whereIn('id', $post_categories)
                    ->values();
                $post['tags'] = $tags
                    ->whereIn('id', $post_tags)
                    ->values();
                $post['cover'] = $attachments
                    ->get()
                    ->where('parent_id', $post->id)
                    ->first();
                $post['likes'] = $likes
                    ->where('kind', 'POST')
                    ->where('parent_id', $post->id)
                    ->values();
                $post['comments'] = $comments
                    ->where('kind', 'POST')
                    ->where('parent_id', $post->id)
                    ->values();
                $post['favorites'] = $favorites
                    ->where('kind', 'POST')
                    ->where('parent_id', $post->id)
                    ->values();
                $post['owner'] = $users
                    ->where('id', $post->user_id)
                    ->first();
                if (intval($post['chapter_id']) > 0) {
                    $post['series'] = collect($chapters)
                        ->where('id', $post->chapter_id)
                        ->first();
                } else {
                    $post['series'] = [];
                }
                return $post;
            });
        return $posts;
    }
}
