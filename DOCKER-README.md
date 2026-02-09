# Simhub Backend - Configuração Docker

Este projeto Laravel está configurado para rodar com Docker, incluindo suporte para SQL Server.

## 📋 Pré-requisitos

- Docker (versão 20.10 ou superior)
- Docker Compose (versão 2.0 ou superior)

## 🚀 Início Rápido

### Opção 1: Setup Automático (Recomendado)

Execute o script de setup automático:

```bash
./setup-docker.sh
```

Este script irá:
- Criar o arquivo `.env` se não existir
- Criar os diretórios necessários
- Fazer build das imagens Docker
- Iniciar os containers
- Instalar dependências do Composer
- Limpar caches

### Opção 2: Setup Manual

1. **Copie o arquivo de ambiente:**
```bash
cp .env.docker .env
```

2. **Ajuste as configurações no arquivo `.env` conforme necessário**

3. **Crie os diretórios necessários:**
```bash
mkdir -p storage/framework/{cache/data,sessions,views}
mkdir -p storage/logs
mkdir -p bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

4. **Faça o build e inicie os containers:**
```bash
docker compose build
docker compose up -d
```

5. **Instale as dependências:**
```bash
docker compose exec app composer install
```

6. **Gere a chave da aplicação:**
```bash
docker compose exec app php artisan key:generate
```

7. **Limpe o cache:**
```bash
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
```

## 🐳 Serviços Docker

O projeto utiliza os seguintes serviços:

- **app**: Container PHP 8.0-FPM com Laravel e drivers SQL Server
- **nginx**: Servidor web Nginx
- **mysql** (opcional): Banco de dados MySQL local
- **redis** (opcional): Cache e filas

## 🔧 Comandos Úteis

### Gerenciamento de Containers

```bash
# Ver status dos containers
docker compose ps

# Parar todos os containers
docker compose down

# Reiniciar containers
docker compose restart

# Ver logs em tempo real
docker compose logs -f app

# Ver logs do Nginx
docker compose logs -f nginx
```

### Executar Comandos no Container

```bash
# Acessar shell do container
docker compose exec app bash

# Executar comandos artisan
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
docker compose exec app php artisan route:list

# Executar comandos composer
docker compose exec app composer update
docker compose exec app composer require package-name
```

### Cache e Otimização

```bash
# Limpar todos os caches
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
docker compose exec app php artisan route:clear
docker compose exec app php artisan view:clear

# Otimizar para produção
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
docker compose exec app composer dump-autoload -o
```

### Banco de Dados

```bash
# Executar migrations
docker compose exec app php artisan migrate

# Executar migrations com fresh (cuidado: apaga dados)
docker compose exec app php artisan migrate:fresh

# Executar seeders
docker compose exec app php artisan db:seed

# Rollback da última migration
docker compose exec app php artisan migrate:rollback
```

## 🔌 Configuração do Banco de Dados

### SQL Server (Padrão)

O projeto está configurado para usar SQL Server externo. As credenciais estão no arquivo `.env`:

```env
DB_CONNECTION=sqlsrv
DB_HOST=191.252.156.123
DB_PORT=9934
DB_DATABASE=Sim_Monitoramento
DB_USERNAME=SIMHUB31
DB_PASSWORD=SiM_37s2!8UCDpo
```

### MySQL Local (Opcional)

Para usar MySQL local:

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
docker compose down
docker compose up -d
```

## 🌐 Acessando a Aplicação

Após iniciar os containers, a aplicação estará disponível em:

- **URL**: http://localhost:8500
- **API**: http://localhost:8500/api

Você pode alterar a porta editando a variável `SERVER_PORT` no arquivo `.env`.

## 📁 Estrutura de Arquivos Docker

```
.
├── Dockerfile                  # Imagem PHP com Laravel e SQL Server drivers
├── docker-compose.yml          # Orquestração dos serviços
├── .dockerignore              # Arquivos ignorados no build
├── .env.docker                # Template de configuração
├── setup-docker.sh            # Script de setup automático
└── docker/
    └── nginx/
        └── nginx.conf         # Configuração do Nginx
```

## 🔍 Troubleshooting

### Problemas de Permissão

Se encontrar erros de permissão:

```bash
# No host
sudo chown -R $USER:$USER .
chmod -R 775 storage bootstrap/cache

# Reconstruir containers
docker compose down
docker compose build --no-cache
docker compose up -d
```

### Containers não iniciam

```bash
# Ver logs de todos os serviços
docker compose logs

# Verificar status
docker compose ps

# Reconstruir do zero
docker compose down -v
docker compose build --no-cache
docker compose up -d
```

### Erro de conexão com o banco de dados

1. Verifique se as credenciais no `.env` estão corretas
2. Verifique se o host do banco está acessível do container:

```bash
docker compose exec app ping -c 3 191.252.156.123
```

### Erro "Could not find driver"

Isso indica que os drivers SQL Server não foram instalados corretamente. Reconstrua a imagem:

```bash
docker compose down
docker compose build --no-cache app
docker compose up -d
```

## 🔄 Atualizando o Projeto

```bash
# Parar containers
docker compose down

# Atualizar código (git pull, etc)
git pull origin main

# Reconstruir se necessário
docker compose build

# Iniciar containers
docker compose up -d

# Atualizar dependências
docker compose exec app composer install

# Executar migrations
docker compose exec app php artisan migrate

# Limpar caches
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
```

## 📝 Notas Importantes

1. **SQL Server Drivers**: O Dockerfile instala automaticamente os drivers necessários para SQL Server (sqlsrv e pdo_sqlsrv)

2. **Permissões**: Os containers rodam com o mesmo UID/GID do seu usuário para evitar problemas de permissão

3. **Volumes**: Os arquivos do projeto são montados como volumes, permitindo desenvolvimento em tempo real

4. **Produção**: Para produção, considere:
   - Usar imagens multi-stage
   - Não montar volumes de código
   - Habilitar cache de configuração
   - Usar variáveis de ambiente seguras

## 🆘 Suporte

Para mais informações sobre Laravel, consulte:
- [Documentação Laravel](https://laravel.com/docs)
- [Documentação Docker](https://docs.docker.com)
