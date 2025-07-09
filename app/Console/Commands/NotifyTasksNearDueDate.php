<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Jobs\NotificarPrazoProximoJob;
use Carbon\Carbon;

class NotifyTasksNearDueDate extends Command
{
    protected $signature = 'tasks:notify-near-due-date';

    protected $description = 'Enviar notificação para tarefas com prazo próximo ao vencimento';

    public function handle()
    {
        $hoje = Carbon::today();
        $limite = $hoje->copy()->addDays(2);

        $tarefasProximas = Task::whereBetween('data_vencimento', [$hoje, $limite])
            ->where('status', '!=', 'concluida')
            ->get();

        foreach ($tarefasProximas as $tarefa) {
            NotificarPrazoProximoJob::dispatch($tarefa);
        }

        $this->info('Notificações para tarefas próximas ao vencimento enviadas.');
    }
}
