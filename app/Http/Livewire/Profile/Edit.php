<?php

namespace App\Http\Livewire\Profile;

use App\Models\User;
use Livewire\Component;

class Edit extends Component
{
    public $profile;
    public function mount()
    {
        $user_id = auth()->user()->id;
        $profile = User::getUser($user_id);
        $this->profile = $profile;
    }
    public function render()
    {
        return view('livewire.profile.edit');
    }
}
