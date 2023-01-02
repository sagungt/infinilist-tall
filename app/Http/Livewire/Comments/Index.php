<?php

namespace App\Http\Livewire\Comments;

use App\Models\Comment;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $user_id = auth()->user()->id;
        $comments = Comment::getCommentsByOwner($user_id);
        return view('livewire.comments.index', ['comments' => $comments]);
    }
}
