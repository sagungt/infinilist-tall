<?php

namespace App\Http\Livewire\Series;

use App\Models\Chapter;
use Livewire\Component;

class My extends Component
{
    public function render()
    {
        $series = Chapter::getChapters()
            ->where('user_id', auth()->user()->id)
            ->values();
        return view('livewire.series.my', ['series' => $series]);
    }
}
