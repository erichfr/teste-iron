@extends('layouts.app')

@section('title', __('messages.minhas_tarefas'))

@section('content')
    <h1>{{ __('messages.minhas_tarefas') }}</h1>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table" id="tasks-table">
        <thead>
            <tr>
                <th>{{ __('messages.titulo') }}</th>
                <th>{{ __('messages.descricao') }}</th>
                <th>{{ __('messages.data_vencimento') }}</th>
                <th>{{ __('messages.status') }}</th>
                <th>{{ __('messages.prioridade') }}</th>
                <th>{{ __('messages.acoes') }}</th>
            </tr>
        </thead>
        <tbody id="task-list">
            @foreach ($tarefas as $tarefa)
                <tr id="task-{{ $tarefa->id }}">
                    <td>{{ $tarefa->titulo }}</td>
                    <td>{{ $tarefa->descricao }}</td>
                    <td>{{ $tarefa->data_vencimento->format('d/m/Y') }}</td>
                    <td>{{ __('messages.status_' . $tarefa->status) }}</td>
                    <td>{{ __('messages.prioridade_' . $tarefa->prioridade) }}</td>
                    <td>
                        <a href="{{ route('tarefas.edit', $tarefa) }}" class="btn btn-sm btn-primary">{{ __('messages.editar') }}</a>
                        <form action="{{ route('tarefas.destroy', $tarefa) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('{{ __('messages.confirmar_exclusao') }}')">{{ __('messages.excluir') }}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

<script>
    function criarLinhaTarefa(tarefa) {
        return `
            <tr id="task-${tarefa.id}">
                <td>${tarefa.titulo}</td>
                <td>${tarefa.descricao}</td>
                <td>${formatarData(tarefa.data_vencimento)}</td>
                <td>${traduzirStatus(tarefa.status)}</td>
                <td>${traduzirPrioridade(tarefa.prioridade)}</td>
                <td>
                    <a href="/tarefas/${tarefa.id}/edit" class="btn btn-sm btn-primary">{{ __('messages.editar') }}</a>
                    <form action="/tarefas/${tarefa.id}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('{{ __('messages.confirmar_exclusao') }}')">{{ __('messages.excluir') }}</button>
                    </form>
                </td>
            </tr>
        `;
    }

    function formatarData(dataStr) {
        if (!dataStr) return '';
        const d = new Date(dataStr);
        return d.toLocaleDateString('pt-BR');
    }

    function traduzirStatus(status) {
        const map = {
            'pendente': "{{ __('messages.status_pendente') }}",
            'concluida': "{{ __('messages.status_concluida') }}",
            // Adicione outros status que tiver
        };
        return map[status] ?? status;
    }

    function traduzirPrioridade(prioridade) {
        const map = {
            'baixa': "{{ __('messages.prioridade_baixa') }}",
            'media': "{{ __('messages.prioridade_media') }}",
            'alta': "{{ __('messages.prioridade_alta') }}",
        };
        return map[prioridade] ?? prioridade;
    }

    function adicionarTarefaNaTabela(tarefa) {
        if (document.getElementById(`task-${tarefa.id}`)) {
            atualizarTarefaNaTabela(tarefa);
            return;
        }
        const tbody = document.getElementById('task-list');
        tbody.insertAdjacentHTML('afterbegin', criarLinhaTarefa(tarefa));
    }

    function atualizarTarefaNaTabela(tarefa) {
        const linha = document.getElementById(`task-${tarefa.id}`);
        if (!linha) {
            adicionarTarefaNaTabela(tarefa);
            return;
        }
        linha.outerHTML = criarLinhaTarefa(tarefa);
    }

</script>
@endsection
