<?php

namespace App\Http\Livewire\Favorites;

use App\Models\Favorite;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $user_id = auth()->user()->id;
        $favorites = Favorite::getFavoritesByUserId($user_id);
        return view('livewire.favorites.index', ['favorites' => $favorites]);
    }
}
