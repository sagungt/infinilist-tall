<?php

namespace App\Http\Livewire\Likes;

use App\Models\Like;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $user_id = auth()->user()->id;
        $likes = Like::getLikesByOwner($user_id);
        return view('livewire.likes.index', ['likes' => $likes]);
    }
}
