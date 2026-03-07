<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sprint extends Model
{
    use HasFactory;

    /**
     * The one to many relationship between a spirnt and a task.
     * Sprint is the parent model while the task is the child model.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * The one to many relationship between a sprint and a project.
     * Sprint is the child model while the project is the parent model.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
