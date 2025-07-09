<?php

namespace App\Http\Controllers;

use App\Events\TaskCreated;
use App\Events\TaskDeleted;
use App\Events\TaskUpdated;
use App\Jobs\NotificarAtribuicaoTarefaJob;
use App\Jobs\SendTaskCreatedEmail;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tarefas = Task::where(function ($query) {
            $query->where('user_id', Auth::id())
                ->orWhere('usuario_atribuido_id', Auth::id());
        })->paginate(10);

        return view('tarefas.index', compact('tarefas'));
    }

    public function create()
    {
        $usuarios = User::all();

        return view('tarefas.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_vencimento' => 'required|date',
            'status' => 'required|in:pendente,em_progresso,concluida',
            'prioridade' => 'required|in:baixa,media,alta',
            'usuario_atribuido_id' => 'nullable|exists:users,id',
        ]);

        $task = Task::create([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'data_vencimento' => $request->data_vencimento,
            'status' => $request->status,
            'prioridade' => $request->prioridade,
            'user_id' => Auth::id(),
            'usuario_atribuido_id' => $request->usuario_atribuido_id,
        ]);

        event(new TaskCreated($task));

        if ($request->filled('usuario_atribuido_id')) {
            $usuarioAtribuido = User::find($request->usuario_atribuido_id);
            NotificarAtribuicaoTarefaJob::dispatch($usuarioAtribuido, $task);
        }

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

        $taskData = (object)[
            'id' => $tarefa->id,
            'user_id' => $tarefa->user_id,
            'usuario_atribuido_id' => $tarefa->usuario_atribuido_id,
        ];

        $tarefa->delete();

        event(new TaskDeleted($taskData));

        return redirect()->route('tarefas.index')->with('success', 'Tarefa excluída com sucesso!');
    }
}
