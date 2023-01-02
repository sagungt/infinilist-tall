<?php

namespace App\Http\Livewire\Posts;

use App\Models\Post;
use Livewire\Component;

class Edit extends Component
{
    public $slug;
    public function mount($slug)
    {
        $this->slug = $slug;
    }
    public function render()
    {
        $post = Post::getPost($this->slug);
        return view('livewire.posts.edit', ['post' => $post]);
    }
}
