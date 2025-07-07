<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskUpdated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $task;

    /**
     * Create a new event instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn()
    {
        return new PrivateChannel('tasks.' . $this->task->user_id);
    }

    public function broadcastAs()
    {
        return 'TaskUpdated';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->task->id,
            'titulo' => $this->task->titulo,
            'descricao' => $this->task->descricao,
            'data_vencimento' => $this->task->data_vencimento->format('Y-m-d'),
            'status' => $this->task->status,
            'prioridade' => $this->task->prioridade,
        ];
    }
}
