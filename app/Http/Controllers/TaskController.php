<?php

namespace App\Http\Controllers;

use App\Events\TaskCreated;
use App\Events\TaskUpdated;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tarefas = Task::where('user_id', Auth::id())->latest()->get();
        return view('tarefas.index', compact('tarefas'));
    }

    public function create()
    {
        return view('tarefas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_vencimento' => 'required|date',
            'status' => 'required|in:pendente,em_progresso,concluida',
            'prioridade' => 'required|in:baixa,media,alta',
        ]);

        $task = Task::create([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'data_vencimento' => $request->data_vencimento,
            'status' => $request->status,
            'prioridade' => $request->prioridade,
            'user_id' => Auth::id(),
        ]);

        event(new TaskCreated($task));

        return redirect()->route('tarefas.index')->with('success', 'Tarefa criada com sucesso!');
    }

    public function show(Task $tarefa)
    {
        $this->authorize('view', $tarefa);
        return view('tarefas.show', compact('tarefa'));
    }

    public function edit(Task $tarefa)
    {
        $this->authorize('update', $tarefa);
        return view('tarefas.edit', compact('tarefa'));
    }

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

        event(new TaskUpdated($tarefa));

        return redirect()->route('tarefas.index')->with('success', 'Tarefa atualizada com sucesso!');
    }

    public function destroy(Task $tarefa)
    {
        $this->authorize('delete', $tarefa);

        $tarefa->delete();

        return redirect()->route('tarefas.index')->with('success', 'Tarefa excluída com sucesso!');
    }
}
