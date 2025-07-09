<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;

class TarefaVencimentoSeeder extends Seeder
{
    public function run(): void
    {
        $usuario = User::first();

        if (!$usuario) {
            $this->command->error('Nenhum usuário encontrado para atribuir tarefa.');
            return;
        }

        $vencimento = now()->addMinutes(30)->startOfMinute();

        $tarefa = Task::create([
            'titulo' => '🕐 Tarefa de Teste - Vence em 30 minutos',
            'descricao' => 'Criada via seeder para testar alerta automático.',
            'data_vencimento' => $vencimento,
            'status' => 'pendente',
            'prioridade' => 'alta',
            'user_id' => $usuario->id,
            'usuario_atribuido_id' => $usuario->id,
        ]);

        $this->command->info("✅ Tarefa criada com vencimento em: " . $vencimento->format('d/m/Y H:i:s'));
    }
}


