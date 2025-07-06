@extends('layouts.app')

@section('title', __('messages.editar_tarefa'))

@section('content')
    <h1>{{ __('messages.editar_tarefa') }}</h1>

    <form action="{{ route('tarefas.update', $tarefa) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Campos iguais ao create, só mudam os valores preenchidos -->
        <!-- Use old() com fallback para $tarefa -->

        <div class="mb-3">
            <label for="titulo" class="form-label">{{ __('messages.titulo') }}</label>
            <input type="text" name="titulo" class="form-control @error('titulo') is-invalid @enderror" id="titulo" value="{{ old('titulo', $tarefa->titulo) }}">
            @error('titulo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="descricao" class="form-label">{{ __('messages.descricao') }}</label>
            <textarea name="descricao" class="form-control @error('descricao') is-invalid @enderror" id="descricao" rows="3">{{ old('descricao', $tarefa->descricao) }}</textarea>
            @error('descricao')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="data_vencimento" class="form-label">{{ __('messages.data_vencimento') }}</label>
            <input type="date" name="data_vencimento" class="form-control @error('data_vencimento') is-invalid @enderror" id="data_vencimento" value="{{ old('data_vencimento', $tarefa->data_vencimento->format('Y-m-d')) }}">
            @error('data_vencimento')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">{{ __('messages.status') }}</label>
            <select name="status" class="form-select @error('status') is-invalid @enderror" id="status">
                <option value="pendente" {{ old('status', $tarefa->status) == 'pendente' ? 'selected' : '' }}>{{ __('messages.status_pendente') }}</option>
                <option value="em_progresso" {{ old('status', $tarefa->status) == 'em_progresso' ? 'selected' : '' }}>{{ __('messages.status_em_progresso') }}</option>
                <option value="concluida" {{ old('status', $tarefa->status) == 'concluida' ? 'selected' : '' }}>{{ __('messages.status_concluida') }}</option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="prioridade" class="form-label">{{ __('messages.prioridade') }}</label>
            <select name="prioridade" class="form-select @error('prioridade') is-invalid @enderror" id="prioridade">
                <option value="baixa" {{ old('prioridade', $tarefa->prioridade) == 'baixa' ? 'selected' : '' }}>{{ __('messages.prioridade_baixa') }}</option>
                <option value="media" {{ old('prioridade', $tarefa->prioridade) == 'media' ? 'selected' : '' }}>{{ __('messages.prioridade_media') }}</option>
                <option value="alta" {{ old('prioridade', $tarefa->prioridade) == 'alta' ? 'selected' : '' }}>{{ __('messages.prioridade_alta') }}</option>
            </select>
            @error('prioridade')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">{{ __('messages.atualizar') }}</button>
        <a href="{{ route('tarefas.index') }}" class="btn btn-secondary">{{ __('messages.cancelar') }}</a>
    </form>
@endsection
