<?php

namespace App\Jobs;

use App\Events\TaskCreated;
use App\Mail\TaskCreatedMail;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendTaskCreatedEmail implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(TaskCreated $event)
    {
        $task = $event->task->load('user');

        if (!$task->user) {
            Log::error("Task {$task->id} sem usuário associado.");
            return;
        }

        Mail::to($task->user->email)
            ->send(new TaskCreatedMail($task));
    }
}
