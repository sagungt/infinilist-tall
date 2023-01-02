<?php

namespace App\Http\Livewire\Cards;

use Livewire\Component;

class Card extends Component
{
    public $post;
    public function render()
    {
        return view('livewire.cards.card');
    }
}
