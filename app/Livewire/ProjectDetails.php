<?php

declare(strict_type=1);

namespace App\Livewire;

use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

/**
 * One single instance of it (same key for all of them) serves all projects.
 */
class ProjectDetails extends Component
{
    /**
     * Making the data property as reactive is better than changing the key of the component
     * Changing the key based on data destroys the component and re-create it again which make flickering
     * when moving between different projects
     * While making it reactive only trigger re-rendering which make a smooth transition (component data changes only).
     */
    #[Reactive]
    public array $data;

    #[Computed]
    public function project(): Project
    {
        return Project::find($this->data['project_id']);
    }

    #[Computed]
    public function phases(): Collection
    {
        return $this->project
            ->statuses()
            ->select('id', 'status', 'sort_order', 'color')
            ->get()
            ->sortBy('sort_order');
    }

    #[Computed]
    public function teamMembers(): Collection
    {
        return $this->project
            ->team()
            ->select('id', 'first_name', 'last_name', 'image')
            ->with(['conversations' => function ($query): void {
                $query->select('id')->whereHas('users', fn ($q) => $q->where('id', Auth::id()));
            }])
            ->get()
            ->map(function ($member) {
                if ($member->id === Auth::id()) {
                    $member->setRelation('conversations', collect());
                }

                return $member;
            });
        // return Cache::remember('project_team_members_' . $this->data['project_id'] . '_user_' . Auth::id(), 3600, function () {
        // });
    }

    /**
     * Need optimization because we invalidate the all sprints with tasks to update one task position
     * so we ran a big query to just update task position (will change this approach later).
     */
    #[On('refresh-tasks')]
    public function refreshTasks(): void
    {
        unset($this->sprints);
    }

    #[Computed]
    public function sprints(): Collection
    {
        $sprints = $this->project
            ->sprints()
            ->select('id', 'name', 'start_date', 'end_date', 'is_closed')
            ->with([
                'tasks:id,title,description,sprint_id,assigned_to,created_by,status_id,priority',
                'tasks.assignedTo:id,first_name,last_name,image',
                'tasks.createdBy:id,first_name,last_name,image',
                'tasks.status:id,status',
            ])->get();

        $sprints->map(function ($sprint) {
            $sprint->setRelation('tasks', $sprint->tasks->groupBy('status.status'));

            return $sprint;
        });

        return $sprints;
    }

    public function render()
    {
        return view('livewire/components/project-details');
    }
}
