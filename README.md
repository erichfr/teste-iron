# IronFence - Sistema de Gerenciamento de Tarefas (FullStack Sênior)

## Visão Geral do Projeto
Sistema completo com:
- Autenticação de usuários
- CRUD de tarefas com prioridades
- Notificações em tempo real via WebSocket
- Processamento assíncrono com Redis
- Dashboard de monitoramento (Horizon)

## Pré-requisitos
- **Docker** + **Docker Compose**
- 4GB de RAM disponível
- Git (opcional)

## Instalação Rápida
```bash
# 1. Clone o repositório
git clone https://github.com/erichfr/teste-iron && cd teste-iron

# 2. Crie o arquivo .env
cp .env.example .env

# 3. Inicie os containers
docker-compose up -d --build

# 4. Instale as dependências
docker-compose exec app composer install
docker-compose exec app npm install

# 5. Execute as migrações
docker-compose exec app php artisan migrate

# 6. Gere a Key
docker-compose exec app php artisan key:generate

# 7. Dê permissão as pastas
- Entre no container e rode os comandos abaixo
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache



```
---

## Autenticação

A autenticação é obrigatória para acessar a maioria dos recursos.

- Middleware aplicado: `auth` e `verified` (para `/dashboard`)

---

## Dashboard

| Método | Rota         | Middleware        | Descrição            |
|--------|--------------|-------------------|----------------------|
| GET    | `/dashboard` | auth, verified     | Retorna a view do dashboard. |

---

## Perfil do Usuário

| Método | Rota        | Nome da Rota      | Descrição                              |
|--------|-------------|-------------------|----------------------------------------|
| GET    | `/profile`  | `profile.edit`    | Exibe o formulário para editar perfil. |
| PATCH  | `/profile`  | `profile.update`  | Atualiza os dados do perfil.           |
| DELETE | `/profile`  | `profile.destroy` | Deleta a conta do usuário.             |

---

## Tarefas (CRUD)

Prefixo: `/tarefas`  
Controller: `TaskController`  
Middleware: `auth`

| Método  | Rota             | Ação          | Descrição                       |
|---------|------------------|---------------|----------------------------------|
| GET     | `/tarefas`       | index         | Lista todas as tarefas.         |
| GET     | `/tarefas/create`| create        | Formulário para nova tarefa.    |
| POST    | `/tarefas`       | store         | Salva nova tarefa.              |
| GET     | `/tarefas/{id}`  | show          | Exibe detalhes da tarefa.       |
| GET     | `/tarefas/{id}/edit` | edit     | Formulário para editar tarefa.  |
| PUT/PATCH | `/tarefas/{id}`| update        | Atualiza a tarefa.              |
| DELETE  | `/tarefas/{id}`  | destroy       | Remove a tarefa.                |

---

## Troca de Idioma

| Método | Rota             | Descrição                         |
|--------|------------------|----------------------------------|
| GET    | `/locale/{lang}` | Define o idioma da aplicação. Aceita: `en`, `pt`. |

---

## Canais de Broadcast

| Canal                 | Autorização                         |
|-----------------------|-------------------------------------|
| `tasks.{userId}`      | Apenas o próprio usuário autenticado pode acessar seu canal. |

---

## Middleware Utilizados

- `auth`: Exige usuário autenticado.
- `verified`: Exige e-mail verificado (para `/dashboard`).

---

## Notas

- Certifique-se de configurar os middlewares e permissões corretamente.
- Para usar o broadcast, configure o Laravel Echo + Pusher ou outra stack WebSocket.



---

## API RESTful (não implementada neste teste)

>  Esta seção descreve uma funcionalidade **planejada**, mas **não incluída na entrega deste teste técnico**.

O projeto foi estruturado pensando em flexibilidade de interface e integração futura com APIs RESTful e WebSocket.

### Opção 1: Livewire + Bootstrap
- API REST será opcional.
- WebSocket será opcional, apenas para melhorias em tempo real.

### Opção 2: Blade + Bootstrap + React
- API REST será necessária para o consumo via React.
- WebSocket será usado para sincronização em tempo real.

### Segurança
- Todos os endpoints da API serão protegidos com autenticação (`auth`).
- Planeja-se uso de **rate limiting** via middleware `throttle`.

---

>  Caso este projeto evolua além do teste, essas funcionalidades poderão ser implementadas conforme a necessidade.


