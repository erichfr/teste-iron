<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskCreated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('tasks.' . $this->task->user_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->task->id,
            'titulo' => $this->task->titulo,
            'descricao' => $this->task->descricao,
            'data_vencimento' => $this->task->data_vencimento,
            'status' => $this->task->status,
            'prioridade' => $this->task->prioridade,
            'created_at' => $this->task->created_at->toDateTimeString(),
        ];
    }

    public function broadcastAs()
    {
        return 'TaskCreated';
    }
}
