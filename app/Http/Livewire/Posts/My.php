<?php

namespace App\Http\Livewire\Posts;

use App\Models\Post;
use Livewire\Component;

class My extends Component
{
    public function render()
    {
        $posts = Post::getPosts()
            ->where('user_id', auth()->user()->id)
            ->values();

        return view('livewire.posts.my', ['posts' => $posts]);
    }
}
