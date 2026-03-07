<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Sidebar extends Component
{
    public function placeholder()
    {
        return <<<'HTML'
        <aside>
            <!-- Loading spinner... -->
            <h2 style="background: black; height: 100vh">Loading......</h2>
        </aside>
        HTML;
    }

    public function render()
    {
        $authUser = Auth::user();

        return view('livewire/components/sidebar', [
            'my_projects' => [
                ...$authUser->myProjects()->select('id', 'title')->get(),
                ...$authUser->projectsAsMember()->select('id', 'title')->get(),
            ],
        ]);
    }
}
