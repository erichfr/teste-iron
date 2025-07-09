<?php

namespace App\Jobs;

use App\Models\Task;
use App\Mail\PrazoProximoMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotificarPrazoProximoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function handle()
    {
        $usuario = $this->task->usuario_atribuido_id ? $this->task->usuarioAtribuido : $this->task->user;

        if ($usuario && $usuario->email) {
            Mail::to($usuario->email)->send(new PrazoProximoMail($this->task));
        }
    }
}
