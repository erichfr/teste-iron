@component('mail::message')
# Nova Tarefa Criada

Olá {{ $task->user->name }},

Uma nova tarefa foi criada com os seguintes detalhes:

- **Título**: {{ $task->titulo }}
- **Descrição**: {{ $task->descricao }}
- **Vencimento**: {{ \Carbon\Carbon::parse($task->data_vencimento)->format('d/m/Y') }}
- **Prioridade**: {{ ucfirst($task->prioridade) }}
- **Status**: {{ ucfirst(str_replace('_', ' ', $task->status)) }}

@component('mail::button', ['url' => route('tarefas.index')])
Ver Tarefas
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent


