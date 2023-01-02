<?php

namespace App\Http\Livewire\Comments;

use Livewire\Component;

class Form extends Component
{
    public $comment = '';
    public $parent_comment_id = 0;
    public $kind = '';
    public $parent_id = '';
    public $profile_url = null;
    public $name = '';
    public function render()
    {
        return view('livewire.comments.form');
    }
}
