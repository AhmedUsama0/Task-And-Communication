<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email;

    public $password;

    public $remember;

    public function authenticate()
    {
        $credintials = $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credintials, $this->remember)) {
            session()->regenerate();

            return $this->redirectRoute('home', [
                'locale' => app()->getLocale(),
            ]);
        }

        $this->addError('email', __('Invalid credentials'));
    }

    public function render()
    {
        // The layout path defined in livewire.php config file instead of here
        return view('livewire/pages/login')
            ->title('Login');
    }
}
