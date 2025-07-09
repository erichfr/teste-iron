<?php

// app/Jobs/EnviarAlertaTarefaJob.php

namespace App\Jobs;

use App\Models\Task;
use App\Mail\AlertaTarefaProximaMail;
use App\Events\TaskDeadlineAlert;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EnviarAlertaTarefaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function handle()
    {
        $usuario = $this->task->usuarioAtribuido ?? $this->task->user;

        if ($usuario && $usuario->email) {
            Mail::to($usuario->email)->send(new AlertaTarefaProximaMail($this->task));
        }

        broadcast(new TaskDeadlineAlert($this->task))->toOthers();
    }
}

