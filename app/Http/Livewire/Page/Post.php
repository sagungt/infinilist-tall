<?php

namespace App\Http\Livewire\Page;

use Illuminate\Routing\Route;
use Livewire\Component;

class Post extends Component
{
    public $slug;
    public $post;
    public $comments = [];
    public $likes = [];

    public function mount($slug)
    {
        $this->slug = $slug;
    }

    public function render()
    {
        return view('livewire.page.post');
    }
}
