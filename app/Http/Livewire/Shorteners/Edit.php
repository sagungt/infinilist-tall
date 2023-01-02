<?php

namespace App\Http\Livewire\Shorteners;

use App\Models\Shortener;
use Livewire\Component;

class Edit extends Component
{
    public $shortener_id;
    public function mount($id)
    {
        $this->shortener_id = $id;
    }
    public function render()
    {
        $shortener = Shortener::query()
            ->where('id', $this->shortener_id)
            ->first();
        return view('livewire.shorteners.edit', ['shortener' => $shortener]);
    }
}
