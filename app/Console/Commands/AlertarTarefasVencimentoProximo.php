<?php

// app/Console/Commands/AlertarTarefasVencimentoProximo.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Events\TaskDeadlineAlert;
use App\Jobs\EnviarAlertaTarefaJob;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AlertarTarefasVencimentoProximo extends Command
{
    protected $signature = 'tasks:alertar-vencimento';
    protected $description = 'Dispara alerta 30 minutos antes da tarefa vencer';

    public function handle()
    {
        $agora = Carbon::now();
        $limiteInferior = $agora->copy()->addMinutes(29);
        $limiteSuperior = $agora->copy()->addMinutes(31);

        $tarefas = Task::whereBetween('data_vencimento', [$limiteInferior, $limiteSuperior])
            ->whereNull('alerta_enviado_em')
            ->where('status', '!=', 'concluida')
            ->get();
 
        foreach ($tarefas as $tarefa) {
            EnviarAlertaTarefaJob::dispatch($tarefa);
            $tarefa->update(['alerta_enviado_em' => now()]);
            Log::info("Alerta disparado para tarefa ID {$tarefa->id}");
        }

        $this->info("Verificação finalizada. Total: {$tarefas->count()}");
    }
}

