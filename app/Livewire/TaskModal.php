<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Events\CommentPosted;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TaskModal extends Component
{
    public int $activeTaskId;

    public string $comment;

    /**
     * Change task status.
     *
     * @param int status_id
     */
    public function changeTaskStatus(int $status_id): void
    {
        Task::where('id', $this->activeTaskId)->update(['status_id' => $status_id]);
        $this->dispatch('refresh-tasks');
    }

    /**
     * Fetch comments for the opened task.
     */
    public function fetchComments(): Collection
    {
        return Comment::where('task_id', $this->activeTaskId)->with(['comment_by:id,first_name,last_name,image'])->get();
    }

    /**
     * handle a new comment for a task in real time.
     */
    public function handleNewComment(): void
    {
        $this->validate([
            'comment' => 'required|string',
            'activeTaskId' => 'required|int|exists:tasks,id',
        ]);

        $comment = Comment::create([
            'content' => trim($this->comment),
            'task_id' => $this->activeTaskId,
            'created_by' => Auth::id(),
        ])->load(['comment_by:id,first_name,last_name,image']);

        CommentPosted::dispatch($comment);
        $this->reset('comment');
    }

    // #[On('echo:my-channel,CommentPosted')]
    // public function notifyNewOrder()
    // {
    //     dd('test');
    // }

    /**
     * Define custom validation messages for properties.
     */
    protected function messages(): array
    {
        return [
            'comment.required' => 'You cannot leave an empty comment.',
            'activeTaskId.exists' => 'That task no longer exists in our records.',
        ];
    }

    /**
     * Render the component.
     *
     * @return View
     */
    public function render()
    {
        return view('livewire/components/task-modal');
    }
}
