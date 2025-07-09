<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskDeadlineAlert implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Task $task) {}

    public function broadcastOn()
    {
        return new PrivateChannel('tasks.' . ($this->task->usuario_atribuido_id ?? $this->task->user_id));
    }

    public function broadcastAs()
    {
        return 'TaskDeadlineAlert';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->task->id,
            'titulo' => $this->task->titulo,
            'data_vencimento' => $this->task->data_vencimento->format('d/m/Y H:i'),
        ];
    }
}

