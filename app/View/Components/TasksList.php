<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TasksList extends Component
{
    /**
     * Create a new component instance.
     *
     * @param  mixed  $tasks
     */
    public function __construct(
        public $tasks,
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.tasks-list');
    }
}
