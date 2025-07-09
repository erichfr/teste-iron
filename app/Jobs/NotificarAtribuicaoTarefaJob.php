<?php

namespace App\Jobs;

use App\Mail\TarefaAtribuidaMail;
use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotificarAtribuicaoTarefaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected User $usuario;
    protected Task $tarefa;

    public function __construct(User $usuario, Task $tarefa)
    {
        $this->usuario = $usuario;
        $this->tarefa = $tarefa;
    }

    public function handle(): void
    {
        Mail::to($this->usuario->email)
            ->send(new TarefaAtribuidaMail($this->tarefa));
    }
}


