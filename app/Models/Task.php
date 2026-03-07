<?php

namespace App\Models;

use App\Enums\TaskPriority;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Laravel\Scout\Searchable;

class Task extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'title',
        'description',
        'completed',
    ];

    protected $casts = [
        'priority' => TaskPriority::class,
    ];

    /**
     * Define the data send to the meilisearch.
     * Every time you change the data, you need to restart the redis worker to pick up the new definition.
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'project' => $this->project->title,
            'assigned_to' => $this->assignedTo->getFullName(),
        ];
    }

    /**
     * Eager loading the realtionship to avoid N+1 query.
     *
     * @return this
     */
    public function makeAllSearchableUsing(Builder $query)
    {
        return $query->select('id', 'title')->with(['project:title', 'assignedTo:first_name,last_name']);
    }

    /**
     * Define the index name of the task model in meiliserach.
     */
    public function searchableAs(): string
    {
        return 'tasks_index';
    }

    /**
     * One to one relationship between the user assigned to a task and the task itself.
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * One to one relationship between the user created a taks and the task itself.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * One to many relationship between statuses and tasks.
     * BelongsTo means rleated Model (status) belongs to the Task model so the Task Model has status_id foregin key.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * One to many relationship between sprints and tasks.
     */
    public function sprint(): BelongsTo
    {
        return $this->belongsTo(Sprint::class);
    }

    /**
     * Get the project model from the task model through the sprint model.
     */
    public function project(): HasOneThrough
    {
        return $this->hasOneThrough(
            Project::class, // Final destination model
            Sprint::class, // Intermediate model
            'id', // Primary key of the intermediate model (Sprint)
            'id', // Primary key of the final model (Project)
            'sprint_id', // Foreign key on the current model (Task)
            'project_id', // Foreign key on the intermediate model (Sprint)
        );
    }
}
