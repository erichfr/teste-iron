<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskDeleted implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $taskId;
    public $userId;

    public function __construct(Task $task)
    {
        $this->taskId = $task->id;
        $this->userId = $task->user_id;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('tasks.' . $this->userId);
    }

    public function broadcastAs()
    {
        return 'TaskDeleted';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->taskId,
        ];
    }
}
