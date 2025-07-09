@component('mail::message')
# Aviso: Prazo Próximo para a Tarefa "{{ $task->titulo }}"

Olá {{ $task->usuarioAtribuido ? $task->usuarioAtribuido->name : $task->user->name }},

A tarefa abaixo tem o prazo de vencimento próximo:

@component('mail::panel')
**Título:** {{ $task->titulo }}
**Descrição:** {{ $task->descricao ?? 'Sem descrição' }}
**Data de Vencimento:** {{ $task->data_vencimento->format('d/m/Y') }}
**Status:** {{ ucfirst($task->status) }}
**Prioridade:** {{ ucfirst($task->prioridade) }}
@endcomponent

Por favor, verifique e atualize a tarefa conforme necessário.

Obrigado,<br>
@endcomponent

