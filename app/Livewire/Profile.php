<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Profile extends Component
{
    public User $user;

    //  public function placeholder() {
    //     return view('components.loader');
    // }

    public function mount(): void
    {
        $this->user = Auth::user();
    }

    public function render()
    {
        return view('livewire/components/profile');
    }
}
