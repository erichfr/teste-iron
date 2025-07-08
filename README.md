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

```

Continua...

# 5. Execute as migrações
docker-compose exec app php artisan migrate

# 6. Compile os assets (opcional)
docker-compose exec app npm run dev

