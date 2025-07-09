<?php

namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AlertaTarefaProximaMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Task $task) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Alerta: Sua tarefa vence em 30 minutos');
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.tasks.alerta_prazo',
            with: ['task' => $this->task]
        );
    }

}
