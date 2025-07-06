<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // Listar tarefas do usuário autenticado
    public function index()
    {
        $tarefas = Task::where('user_id', Auth::id())->latest()->get();
        return view('tarefas.index', compact('tarefas'));
    }

    // Mostrar formulário de criação
    public function create()
    {
        return view('tarefas.create');
    }

    // Armazenar nova tarefa
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_vencimento' => 'required|date',
            'status' => 'required|in:pendente,em_progresso,concluida',
            'prioridade' => 'required|in:baixa,media,alta',
        ]);

        Task::create([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'data_vencimento' => $request->data_vencimento,
            'status' => $request->status,
            'prioridade' => $request->prioridade,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('tarefas.index')->with('success', 'Tarefa criada com sucesso!');
    }

    // Mostrar uma tarefa específica (opcional)
    public function show(Task $tarefa)
    {
        $this->authorize('view', $tarefa);
        return view('tarefas.show', compact('tarefa'));
    }

    // Mostrar formulário de edição
    public function edit(Task $tarefa)
    {
        $this->authorize('update', $tarefa);
        return view('tarefas.edit', compact('tarefa'));
    }

    // Atualizar tarefa
    public function update(Request $request, Task $tarefa)
    {
        $this->authorize('update', $tarefa);

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_vencimento' => 'required|date',
            'status' => 'required|in:pendente,em_progresso,concluida',
            'prioridade' => 'required|in:baixa,media,alta',
        ]);

        $tarefa->update($request->all());

        return redirect()->route('tarefas.index')->with('success', 'Tarefa atualizada com sucesso!');
    }

    // Deletar tarefa
    public function destroy(Task $tarefa)
    {
        $this->authorize('delete', $tarefa);

        $tarefa->delete();

        return redirect()->route('tarefas.index')->with('success', 'Tarefa excluída com sucesso!');
    }
}
