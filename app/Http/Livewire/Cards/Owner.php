<?php

namespace App\Http\Livewire\Cards;

use Livewire\Component;

class Owner extends Component
{
    public $name;
    public $username;
    public $created_at;
    public $profile_url;
    
    public function mount($owner, $created_at)
    {
        $this->name = $owner['name'];
        $this->username = $owner['username'];
        $this->created_at = $created_at;
        if ($owner['profile_url'] == null) {
            $this->profile_url = null;
        } else {
            $this->profile_url = $owner['profile_url']['path'];
        }
    }

    public function render()
    {
        return view('livewire.cards.owner');
    }
}
