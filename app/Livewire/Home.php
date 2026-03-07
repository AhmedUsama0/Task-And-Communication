<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Home extends Component
{
    public $tasks;

    protected $listeners = ['task-created' => 'reloadTasks', 'task-removed' => '$refresh'];

    public function mount(): void
    {
        $this->tasks = [];
    }

    public function filterTasks(string $filter): void
    {
        $tasksQueryBuilder = auth()->user()->tasks();
        $userId = auth()->user()->id;
        $cacheKey = "tasks_{$userId}_{$filter}";

        $this->tasks = Cache::remember($cacheKey, 600, function () use ($tasksQueryBuilder, $filter) {
            return match ($filter) {
                'all' => $tasksQueryBuilder->get(),
                'completed' => $tasksQueryBuilder->where('completed', true)->get(),
                'uncompleted' => $tasksQueryBuilder->where('completed', false)->get(),
            };
        });
    }

    public function reloadTasks(): void
    {
        $this->tasks = auth()->user()->tasks;
    }

    public function render()
    {
        return view('livewire/pages/home')->title('Home');
    }
}
