<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class Navbar extends Component
{
    use WithFileUploads;

    public $photo;

    public function logout(): void
    {
        Auth::logout();
    }

    public function changeLocale($selectedLocale): void
    {
        $currentLocale = app()->currentLocale();
        $currentUrl = url()->previous();
        $newUrl = str_replace($currentLocale, $selectedLocale, $currentUrl);

        redirect($newUrl);
    }

    public function save(): void
    {
        $this->validate([
            'photo' => 'image|max:1024',
        ]);

        $path = $this->photo->storeAs(path: 'profile-pictures', name: 'user-'.Auth::id().$this->photo->extension(), options: 's3');

        if (! $path) {
            $this->addError('photo', __('Something wrong when changing the profie picture. Please try again later.'));

            return;
        }

        $isUpdated = Auth::user()->update(['image' => $path]);

        if (! $isUpdated) {
            $this->addError('photo', 'Something wrong. Please contact us.');

            return;
        }

        session()->flash('success', __('Image is changed sucessfully'));
    }

    public function render()
    {
        return view('livewire/components/navbar');
    }
}
