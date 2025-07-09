@component('mail::message')
# ⚠️ Alerta: Tarefa prestes a vencer!

A tarefa **"{{ $task->titulo }}"** tem vencimento às {{ $task->data_vencimento->format('H:i \d\e d/m/Y') }}.

@component('mail::panel')
**Descrição:** {{ $task->descricao ?? 'Sem descrição' }}
**Prioridade:** {{ ucfirst($task->prioridade) }}
@endcomponent

Acesse o sistema e conclua a tarefa o quanto antes.

@component('mail::button', ['url' => route('tarefas.show', $task->id)])
Ver Tarefa
@endcomponent

@endcomponent
