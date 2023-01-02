<?php

namespace App\Http\Livewire\Comments;

use Livewire\Component;

class Item extends Component
{
    public $comment = '';
    public $comment_id = 0;
    public $parent_id = 0;
    public $profile_url = null;
    public $name = '';
    public $is_edited = false;
    public $is_pinned = false;
    public $updated_at = '';
    public $like_count = '';
    public function render()
    {
        return view('livewire.comments.item');
    }
}
