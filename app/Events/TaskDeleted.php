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

    public $task;

    public function __construct($task)
    {
        $this->task = $task;
    }

    public function broadcastOn()
    {
        $channels = [];

        if (!empty($this->task->user_id)) {
            $channels[] = new PrivateChannel('tasks.' . $this->task->user_id);
        }

        if (!empty($this->task->usuario_atribuido_id) &&
            $this->task->usuario_atribuido_id !== $this->task->user_id) {
            $channels[] = new PrivateChannel('tasks.' . $this->task->usuario_atribuido_id);
        }

        return $channels;
    }

    public function broadcastAs()
    {
        return 'TaskDeleted';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->task->id,
        ];
    }
}


