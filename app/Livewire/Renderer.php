<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class Renderer extends Component
{
    public string $component = 'profile';

    public array $data = [];

    #[On('switch-component')]
    public function switchComponent(string $component, array $data = []): void
    {
        $this->component = $component;
        $this->data = $data;
    }

    public function render()
    {
        return view('livewire/components/renderer');
    }
}
