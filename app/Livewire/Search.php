<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Search extends Component
{
    public string $query;

    /**
     * Get search results for tasks and users.
     */
    #[Computed]
    public function results(): Collection
    {
        return Task::search($this->query)
            ->query(function ($query) {
                return $query->with([
                    'assignedTo:id,first_name,last_name',
                    'createdBy:id,first_name,last_name',
                    'status:id,status', // Task status
                    'project:projects.id,projects.title',
                    'project.statuses:id,status',
                ]);
            })->get()
            ->concat(
                User::search($this->query)
                    ->query(fn ($query) => $query->select('id', 'first_name', 'last_name', 'image'))
                    ->get(),
            );
    }

    public function render()
    {
        return view('livewire/components/search');
    }
}
