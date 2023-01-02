<?php

namespace App\Http\Livewire\Series;

use App\Models\Chapter;
use Livewire\Component;

class Show extends Component
{
    public $slug;
    public function mount($slug)
    {
        $this->slug = $slug;
    }

    public function render()
    {
        $series = Chapter::getChapter($this->slug);
        return view('livewire.series.show', ['series' => $series]);
    }
}
