<?php

namespace App\Http\Livewire\Series;

use Livewire\Component;

class NewSeries extends Component
{
    public $posts = [];
    public function render()
    {
        return view('livewire.series.new-series');
    }
}
