<?php

use App\Models\Task;
use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

Broadcast::channel('task-{taskId}', function ($user, $taskId) {
    $task = Task::with(['sprint:id,project_id'])->find($taskId);

    if (! $task) {
        return false;
    }

    $sprint = $task->sprint;

    if (! $sprint) {
        return false;
    }

    $isOwner = $user->myProjects()->where('id', $sprint->project_id)->exists();
    $isMember = $user->projectsAsMember()->where('projects.id', $sprint->project_id)->exists();

    return $isMember || $isOwner;
});
