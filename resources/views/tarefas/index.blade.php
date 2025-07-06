@extends('layouts.app')

@section('title', __('messages.minhas_tarefas'))

@section('content')
    <h1>{{ __('messages.minhas_tarefas') }}</h1>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
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
    <tbody>
        @foreach ($tarefas as $tarefa)
            <tr>
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

@endsection
