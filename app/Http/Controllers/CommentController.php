<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    private $kinds = ['post', 'series'];
    private $type = 'api';
    public function index($kind, $parent_id)
    {
        $comments = Comment::getCommentsByParentId($kind, $parent_id);

        return response([
            'status' => true,
            'message' => '',
            'data' => $comments,
        ]);
    }

    public function store(Request $request, $kind, $parent_id)
    {
        if (!in_array($kind, $this->kinds)) {
            if ($this->type == 'web') {
                return redirect()->back()->withErrors([
                    'error' => 'Invalid kind'
                ]);
            }
            return response([
                'status' => false,
                'message' => 'Invalid kind',
                'data' => null,
            ], 400);
        }

        if ($kind == 'post') {
            $child = Post::query()->where('id', $parent_id)->first();

            if ($child == null) {
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
        }

        $payload = $request->all();

        $validator = Validator::make($payload, [
            'content' => 'required',
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

        $user_id = auth()->user()->id;

        if (isset($payload['parent_comment_id'])) {
            $comment = Comment::query()
                ->where('id', $payload['parent_comment_id'])
                ->first();

            if ($comment == null) {
                if ($this->type == 'web') {
                    return redirect()->back()->withErrors([
                        'error' => 'Comment not found'
                    ]);
                }
            }
        }

        $payload['kind'] = strtoupper($kind);
        $payload['parent_id'] = $parent_id;
        $payload['user_id'] = $user_id;

        $comment = Comment::query()->create($payload);

        if ($this->type == 'web') {
            return redirect()->back()->with([
                'success' => 'Comment posted'
            ]);
        }
        return response([
            'status' => true,
            'message' => '',
            'data' => $comment,
        ]);
    }

    public function pinComment($id)
    {
        $comment = Comment::query()->where('id', $id)->first();

        if ($comment == null) {
            return response([
                'status' => false,
                'message' => 'Comment not found',
                'data' => null,
            ], 404);
        }

        $comment->fill([
            'is_pinned' => !$comment->is_pinned,
        ]);
        $comment->save();

        return response([
            'status' => true,
            'message' => '',
            'data' => $comment,
        ]);
    }

    public function update(Request $request, $kind, $parent_id, $id)
    {
        $payload = $request->all();

        $validator = Validator::make($payload, [
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 422);
        }

        $comment = Comment::query()
            ->where('kind', strtoupper($kind))
            ->where('parent_id', $parent_id)
            ->where('id', $id)
            ->first();

        if ($comment == null) {
            return response([
                'status' => false,
                'message' => 'Comment not found',
                'data' => null,
            ], 404);
        }

        $comment->fill([
            'content' => $payload['content'],
            'is_edited' => true,
        ]);

        $comment->save();

        return response([
            'status' => true,
            'message' => '',
            'data' => $comment
        ]);
    }

    public function destroy($id)
    {
        $comment = Comment::query()
            ->where('id', $id)
            ->first();

        if ($comment == null) {
            return response([
                'status' => false,
                'message' => 'Comment not found',
                'data' => null,
            ], 404);
        }
        
        $comment->delete();
        
        return response([
            'status' => true,
            'message' => '',
            'data' => null,
        ]);
    }
}
