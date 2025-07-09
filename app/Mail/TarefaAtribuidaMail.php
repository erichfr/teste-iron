<?php

namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TarefaAtribuidaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tarefa;

    public function __construct(Task $tarefa)
    {
        $this->tarefa = $tarefa;
    }

    public function build()
    {
        return $this->subject('Você recebeu uma nova tarefa')
                    ->markdown('emails.tarefa_atribuida');
    }
}
