<?php

namespace App\Livewire;

use App\Models\User;
use Exception;
use Livewire\Component;

class Register extends Component
{
    public $username;

    public $email;

    public $password;

    public $first_name;

    public $last_name;

    public function register(): void
    {
        try {
            $credentials = $this->validate([
                'email' => 'required|email',
                'password' => 'required|min:6',
                'first_name' => 'required',
                'last_name' => 'required',
            ]);

            $user = User::create($credentials);

            if ($user) {
                $message = __('User created successfully. Redirecting you to login page...');

                $this->js(
                    "Swal.fire({
                        title: 'Success',
                        text: '{$message}',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 3000
                     }).then(() => {
                        window.location.href = '/login';
                    });",
                );
            }
        } catch (Exception $e) {
            logger('User not created', ['error' => $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire/pages/register')
            ->title('Register');
    }
}
