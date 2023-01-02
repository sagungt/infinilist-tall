<?php

namespace App\Http\Livewire\Shorteners;

use App\Models\Shortener;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $user_id = auth()->user()->id;
        $shorteners = Shortener::getShortenersByOwner($user_id);
        return view('livewire.shorteners.index', ['shorteners' => $shorteners]);
    }
}
