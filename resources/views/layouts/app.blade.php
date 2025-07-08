<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'App') - {{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">Tarefas</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                    aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item"><a href="{{ route('tarefas.index') }}" class="nav-link">Minhas Tarefas</a></li>
                        <li class="nav-item"><a href="{{ route('tarefas.create') }}" class="nav-link">Nova Tarefa</a></li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-link nav-link" type="submit">Sair</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item"><a href="{{ route('login') }}" class="nav-link">Login</a></li>
                        <li class="nav-item"><a href="{{ route('register') }}" class="nav-link">Registrar</a></li>
                    @endauth

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="langDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ strtoupper(app()->getLocale()) }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="langDropdown">
                            <li><a class="dropdown-item" href="{{ route('locale.switch', 'pt') }}">Português</a></li>
                            <li><a class="dropdown-item" href="{{ route('locale.switch', 'en') }}">English</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container">
        @yield('content')
    </main>

    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Notificação</strong>
                <small>agora</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Fechar"></button>
            </div>
            <div class="toast-body" id="toast-body"></div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.2/dist/echo.iife.min.js"></script>
<script>
    @auth

        const userId = {{ auth()->id() }};

        window.Echo = new window.Echo({
            broadcaster: 'pusher',
            key: '{{ config('broadcasting.connections.pusher.key') }}',
            cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
            wsHost: window.location.hostname,
            wsPort: 6001,
            forceTLS: false,
            disableStats: true,
            enabledTransports: ['ws', 'wss'],
        });

        window.Echo.private(`tasks.${userId}`)
            .listen('.TaskCreated', e => {
                adicionarTarefaNoDOM(e);
                mostrarNotificacao('Nova tarefa criada: ' + e.titulo);
            })
            .listen('.TaskUpdated', e => {
                atualizarTarefaNoDOM(e);
                mostrarNotificacao('Tarefa atualizada: ' + e.titulo);
            })
            .listen('.TaskDeleted', (e) => {
                const row = document.getElementById(`task-${e.id}`);
                if (row) {
                    row.remove();
                    mostrarNotificacao('Tarefa removida com sucesso!');
                }
            });
    @endauth

    function adicionarTarefaNoDOM(tarefa) {
        const tbody = document.querySelector('table tbody');
        if (!tbody) return;

        if (document.getElementById(`task-${tarefa.id}`)) return;

        const tr = document.createElement('tr');
        tr.id = `task-${tarefa.id}`;

        tr.innerHTML = `
            <td>${tarefa.titulo}</td>
            <td>${tarefa.descricao}</td>
            <td>${new Date(tarefa.data_vencimento).toLocaleDateString()}</td>
            <td>${traduzirStatus(tarefa.status)}</td>
            <td>${traduzirPrioridade(tarefa.prioridade)}</td>
            <td>
                <a href="/tarefas/${tarefa.id}/edit" class="btn btn-sm btn-primary">Editar</a>
                <form action="/tarefas/${tarefa.id}" method="POST" style="display:inline-block;" onsubmit="return confirm('Tem certeza que deseja excluir?');">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button class="btn btn-sm btn-danger">Excluir</button>
                </form>
            </td>
        `;

        tbody.prepend(tr);
    }

    function atualizarTarefaNoDOM(tarefa) {
        const tr = document.getElementById(`task-${tarefa.id}`);
        if (!tr) {
            adicionarTarefaNoDOM(tarefa);
            return;
        }
        tr.innerHTML = `
            <td>${tarefa.titulo}</td>
            <td>${tarefa.descricao}</td>
            <td>${new Date(tarefa.data_vencimento).toLocaleDateString()}</td>
            <td>${traduzirStatus(tarefa.status)}</td>
            <td>${traduzirPrioridade(tarefa.prioridade)}</td>
            <td>
                <a href="/tarefas/${tarefa.id}/edit" class="btn btn-sm btn-primary">Editar</a>
                <form action="/tarefas/${tarefa.id}" method="POST" style="display:inline-block;" onsubmit="return confirm('Tem certeza que deseja excluir?');">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button class="btn btn-sm btn-danger">Excluir</button>
                </form>
            </td>
        `;
    }

    function traduzirStatus(status) {
        const map = {
            pendente: 'Pendente',
            em_progresso: 'Em Progresso',
            concluida: 'Concluída'
        };
        return map[status] || status;
    }
    function traduzirPrioridade(prioridade) {
        const map = {
            baixa: 'Baixa',
            media: 'Média',
            alta: 'Alta'
        };
        return map[prioridade] || prioridade;
    }
    function mostrarNotificacao(mensagem) {
        const toastEl = document.getElementById('liveToast');
        const toastBody = document.getElementById('toast-body');
        if (!toastEl || !toastBody) return;

        toastBody.textContent = mensagem;
        new bootstrap.Toast(toastEl).show();
    }

</script>
</body>
</html>
