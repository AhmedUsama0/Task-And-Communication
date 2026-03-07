<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentPosted implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    // Define the queue for the job to be placed which is created from this event
    public $broadcastQueue = 'comments';

    /**
     * CommentPosted constructor.
     */
    public function __construct(public Comment $comment) {}

    /**
     * Braodcast the dispatched event to a specific channel depends on the opened task.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('task-'.$this->comment->task_id),
        ];
    }
}
