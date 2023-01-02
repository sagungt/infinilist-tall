<?php

namespace App\Http\Livewire\Series;

use App\Models\Chapter;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $series = Chapter::getChapters();
        return view('livewire.series.index', ['series' => $series]);
    }
}
