<?php

namespace App\Http\Livewire\Cards;

use Livewire\Component;

class ListPost extends Component
{
    public $posts = [];
    public function render()
    {
        return view('livewire.cards.list-post');
    }
}
