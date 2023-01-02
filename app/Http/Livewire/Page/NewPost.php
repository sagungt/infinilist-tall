<?php

namespace App\Http\Livewire\Page;

use Livewire\Component;

class NewPost extends Component
{
    public $selectedCategories = [];
    public $selectedTags = [];
    public function render()
    {
        return view('livewire.page.new-post');
    }
}
