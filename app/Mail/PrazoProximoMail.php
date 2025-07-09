<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PrazoProximoMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $task;

    public function __construct($task)
    {
        $this->task = $task;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Prazo Próximo: Tarefa ' . $this->task->titulo,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.tasks.prazo_proximo',
            with: [
                'task' => $this->task,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

