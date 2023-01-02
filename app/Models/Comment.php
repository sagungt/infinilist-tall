<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public static function getCommentsByParentId($kind, $parent_id)
    {
        $comments = (new static)::query()
            ->where('deleted_at', null)
            ->where('kind', strtoupper($kind))
            ->where('parent_id', $parent_id)
            ->get();
        $likes = Like::query()
            ->where('kind', 'COMMENT')
            ->get();
        $users = User::getUsers();
        $comments = collect($comments)
            ->map(function ($comment) use ($users, $likes, $comments) {
                $comment['owner'] = $users
                    ->where('id', $comment->user_id)
                    ->first();
                // $comment['replies'] = $comments
                //     ->where('parent_comment_id', $comment['id'])
                //     ->values();
                $comment['likes'] = $likes
                    ->where('parent_id', $comment->id)
                    ->values();
                return $comment;
            });
        return $comments
            // ->filter(fn ($comment) => intval($comment['parent_comment_id']) == 0)
            ->sortBy([
                ['is_pinned', 'ASC'],
                ['created_at', 'DESC'],
            ])
            ->values();
    }

    public static function getCommentsByOwner($user_id) {
        $comments = (new static)::query()
            ->where('user_id', $user_id)
            ->get();

            
        if ($comments) {
            $posts = Post::query()
                ->get();
            $series = Chapter::query()
                ->get();
            $comments = $comments->map(function ($comment) use ($posts, $series, $comments) {
                if ($comment['kind'] == 'POST') {
                    $comment['child'] = $posts
                        ->where('id', $comment->parent_id)
                        ->first();
                }
                if ($comment['kind'] == 'SERIES') {
                    $comment['child'] = $series
                        ->where('id', $comment->parent_id)
                        ->first();
                }
                if (intval($comment->parent_comment_id) > 0) {
                    $comment['reply'] = $comments
                        ->where('id', $comment->parent_comment_id)
                        ->first();
                } else {
                    $comment['reply'] = null;
                }
                return $comment;
            });
        }

        return $comments;
    }
}
