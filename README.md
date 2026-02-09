# Simhub Backend

API backend do projeto Simhub desenvolvida em Laravel 8 com suporte a SQL Server.

## 📋 Índice

- [Tecnologias](#tecnologias)
- [Requisitos](#requisitos)
- [Instalação com Docker](#instalação-com-docker)
- [Comandos Úteis](#comandos-úteis)
- [Estrutura do Projeto](#estrutura-do-projeto)
- [Configuração](#configuração)
- [API Endpoints](#api-endpoints)
- [Troubleshooting](#troubleshooting)

## 🚀 Tecnologias

- **PHP** 8.0
- **Laravel** 8.75
- **SQL Server** (sqlsrv)
- **Docker** & Docker Compose
- **Nginx**
- **Composer**

## 📦 Requisitos

### Para rodar com Docker (Recomendado):
- Docker 20.10+
- Docker Compose 2.0+

### Para rodar sem Docker:
- PHP 8.0+
- Composer
- SQL Server drivers (sqlsrv, pdo_sqlsrv)
- Nginx ou Apache

## 🐳 Instalação com Docker

### 1. Clone o repositório

```bash
git clone <url-do-repositorio>
cd simhub-back
```

### 2. Execute o setup automático

```bash
chmod +x setup-docker.sh
./setup-docker.sh
```

Este script irá:
- ✅ Criar o arquivo `.env` (se não existir)
- ✅ Criar diretórios necessários
- ✅ Fazer build das imagens Docker
- ✅ Iniciar os containers
- ✅ Instalar dependências do Composer
- ✅ Gerar chave da aplicação
- ✅ Limpar caches

### 3. Acesse a aplicação

- **Frontend/API**: http://localhost:8500
- **API Routes**: http://localhost:8500/api

## 🛠️ Comandos Úteis

### Usando Make (Mais Fácil)

```bash
# Gerenciamento de containers
make up              # Iniciar containers
make down            # Parar containers
make restart         # Reiniciar containers
make ps              # Ver status dos containers
make logs            # Ver logs de todos os containers
make logs-app        # Ver logs do container app
make logs-nginx      # Ver logs do nginx

# Desenvolvimento
make shell           # Acessar shell do container
make composer-install # Instalar dependências
make composer-update  # Atualizar dependências

# Artisan
make migrate         # Executar migrations
make migrate-fresh   # Fresh migrations (apaga dados!)
make seed            # Executar seeders
make fresh-seed      # Fresh + seed
make cache-clear     # Limpar todos os caches
make cache-optimize  # Otimizar caches (produção)

# Testes
make test            # Executar testes

# Limpeza
make clean           # Limpar containers e volumes
make rebuild         # Rebuild completo
make permissions     # Ajustar permissões
```

### Usando Docker Compose Diretamente

```bash
# Gerenciamento de containers
docker compose up -d              # Iniciar containers em background
docker compose down               # Parar containers
docker compose restart            # Reiniciar containers
docker compose ps                 # Status dos containers
docker compose logs -f app        # Logs em tempo real

# Executar comandos no container
docker compose exec app bash      # Acessar shell
docker compose exec app php artisan migrate
docker compose exec app composer install

# Rebuild
docker compose down
docker compose build --no-cache
docker compose up -d
```

## 📂 Estrutura do Projeto

```
simhub-back/
├── app/                    # Código da aplicação
│   ├── Http/
│   │   ├── Controllers/   # Controllers
│   │   └── Middleware/    # Middlewares
│   ├── Models/            # Models Eloquent
│   └── Services/          # Serviços
├── config/                # Arquivos de configuração
├── database/
│   ├── migrations/        # Migrations do banco
│   └── seeders/          # Seeders
├── docker/               # Configurações Docker
│   └── nginx/
│       └── nginx.conf    # Configuração Nginx
├── routes/
│   ├── api.php           # Rotas da API
│   └── web.php           # Rotas web
├── storage/              # Logs e cache
├── tests/                # Testes automatizados
├── .env                  # Variáveis de ambiente
├── docker-compose.yml    # Orquestração Docker
├── Dockerfile            # Imagem Docker
├── Makefile             # Comandos simplificados
└── setup-docker.sh      # Script de setup
```

## ⚙️ Configuração

### Variáveis de Ambiente (.env)

```env
# Aplicação
APP_NAME=Simhub
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8500

# Banco de Dados SQL Server
DB_CONNECTION=sqlsrv
DB_HOST=191.252.156.123
DB_PORT=9934
DB_DATABASE=Sim_Monitoramento
DB_USERNAME=SIMHUB31
DB_PASSWORD=SiM_37s2!8UCDpo

# Porta do servidor
SERVER_PORT=8500
```

### Trocar para MySQL Local (Opcional)

1. Descomente o serviço `mysql` no `docker-compose.yml`
2. Atualize o `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=simhub
DB_USERNAME=laravel
DB_PASSWORD=root
```

3. Reinicie os containers:
```bash
make down
make up
```

## 🔌 API Endpoints

### Autenticação
```
POST   /api/login          # Login
POST   /api/logout         # Logout
POST   /api/register       # Registro
```

### Recursos
```
GET    /api/users          # Listar usuários
POST   /api/users          # Criar usuário
GET    /api/users/{id}     # Buscar usuário
PUT    /api/users/{id}     # Atualizar usuário
DELETE /api/users/{id}     # Deletar usuário
```

**Ver todas as rotas:**
```bash
make artisan CMD="route:list"
# ou
docker compose exec app php artisan route:list
```

## 🎯 Comandos Laravel Úteis

### Artisan

```bash
# Migrations
make artisan CMD="migrate"                    # Executar migrations
make artisan CMD="migrate:rollback"           # Reverter última migration
make artisan CMD="migrate:fresh"              # Recriar banco (apaga dados!)
make artisan CMD="migrate:fresh --seed"       # Recriar e popular

# Cache
make artisan CMD="cache:clear"                # Limpar cache
make artisan CMD="config:clear"               # Limpar config cache
make artisan CMD="route:clear"                # Limpar route cache
make artisan CMD="view:clear"                 # Limpar view cache

# Otimização (Produção)
make artisan CMD="config:cache"               # Cache de configuração
make artisan CMD="route:cache"                # Cache de rotas
make artisan CMD="view:cache"                 # Cache de views

# Informações
make artisan CMD="route:list"                 # Listar rotas
make artisan CMD="tinker"                     # Console interativo
make artisan CMD="about"                      # Info da aplicação
```

### Composer

```bash
# Instalar dependências
make composer-install

# Adicionar pacote
docker compose exec app composer require vendor/package

# Remover pacote
docker compose exec app composer remove vendor/package

# Atualizar dependências
make composer-update

# Dump autoload
docker compose exec app composer dump-autoload
```

## 🧪 Testes

```bash
# Executar todos os testes
make test

# Executar testes específicos
docker compose exec app php artisan test --filter=UserTest

# Com coverage
docker compose exec app php artisan test --coverage
```

## 🔍 Debugging

### Ver logs da aplicação

```bash
# Logs em tempo real
make logs-app

# Logs do Laravel
docker compose exec app tail -f storage/logs/laravel.log

# Logs do Nginx
make logs-nginx
```

### Acessar o banco de dados

```bash
# Via tinker
make artisan CMD="tinker"

# Dentro do tinker:
DB::connection()->getPdo();           # Testar conexão
DB::table('users')->get();            # Query direto
User::all();                          # Via model
```

### Inspecionar container

```bash
# Acessar shell
make shell

# Ver processos
docker compose exec app ps aux

# Ver variáveis de ambiente
docker compose exec app env
```

## 🐛 Troubleshooting

### Erro de permissão nos diretórios

```bash
# Ajustar permissões
make permissions

# Ou manualmente
sudo chown -R $USER:$USER .
chmod -R 775 storage bootstrap/cache
```

### Erro "Could not find driver" (SQL Server)

```bash
# Verificar se os drivers estão instalados
docker compose exec app php -m | grep sqlsrv

# Rebuild da imagem
make rebuild
```

### Containers não iniciam

```bash
# Ver logs de erros
docker compose logs

# Limpar tudo e reconstruir
make clean
make rebuild
```

### Erro de conexão com o banco

```bash
# Testar conectividade
docker compose exec app ping -c 3 191.252.156.123

# Verificar configuração
docker compose exec app php artisan tinker
# Dentro do tinker: config('database.connections.sqlsrv')
```

### Porta 8500 já em uso

Edite o `.env` e mude a porta:
```env
SERVER_PORT=8501
```

Reinicie os containers:
```bash
make restart
```

### Limpar tudo e começar do zero

```bash
# Para e remove containers, volumes e imagens
docker compose down -v
docker system prune -f

# Rebuild completo
./setup-docker.sh
```

## 📊 Monitoramento

### Verificar uso de recursos

```bash
# CPU e memória dos containers
docker stats

# Espaço em disco
docker system df
```

### Health check

```bash
# Status dos containers
make ps

# Testar API
curl http://localhost:8500/api/health

# Testar conexão com banco
make artisan CMD="tinker"
# Dentro: DB::connection()->getPdo();
```

## 🚀 Deploy em Produção

### Checklist antes do deploy

- [ ] Configurar `APP_ENV=production` no `.env`
- [ ] Definir `APP_DEBUG=false`
- [ ] Gerar nova `APP_KEY`
- [ ] Configurar credenciais reais do banco
- [ ] Executar `make cache-optimize`
- [ ] Configurar SSL/HTTPS
- [ ] Configurar backup do banco de dados
- [ ] Configurar logs externos (Sentry, etc)

### Comandos de deploy

```bash
# Baixar última versão
git pull origin main

# Rebuild com otimizações
docker compose down
docker compose build --no-cache
docker compose up -d

# Instalar dependências (sem dev)
docker compose exec app composer install --no-dev --optimize-autoloader

# Executar migrations
docker compose exec app php artisan migrate --force

# Otimizar
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
```

## 📚 Recursos Adicionais

- [Documentação Laravel 8](https://laravel.com/docs/8.x)
- [Laravel API Resources](https://laravel.com/docs/8.x/eloquent-resources)
- [Docker Compose](https://docs.docker.com/compose/)
- [SQL Server PHP Drivers](https://docs.microsoft.com/en-us/sql/connect/php/)

## 🤝 Contribuindo

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/MinhaFeature`)
3. Commit suas mudanças (`git commit -m 'Adiciona MinhaFeature'`)
4. Push para a branch (`git push origin feature/MinhaFeature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT.

## 👥 Equipe

- **Desenvolvimento**: Simhub Team
- **Manutenção**: [Seu Nome/Equipe]

## 📞 Suporte

Para questões e suporte:
- 📧 Email: suporte@simhub.com.br
- 🐛 Issues: [GitHub Issues](link-do-repositorio/issues)

---

**Desenvolvido com ❤️ usando Laravel e Docker**
