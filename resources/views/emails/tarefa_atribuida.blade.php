@component('mail::message')
# Nova Tarefa Atribuída

Você foi atribuído à seguinte tarefa:

**Título:** {{ $tarefa->titulo }}
**Descrição:** {{ $tarefa->descricao }}
**Prazo:** {{ $tarefa->data_vencimento->format('d/m/Y') }}

@component('mail::button', ['url' => route('tarefas.show', $tarefa->id)])
Ver Tarefa
@endcomponent

Obrigado,<br>
@endcomponent

