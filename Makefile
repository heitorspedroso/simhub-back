.PHONY: help build up down restart logs shell composer artisan clean migrate fresh seed cache-clear test

# Detectar se está usando docker compose ou docker-compose
DOCKER_COMPOSE := $(shell command -v docker compose 2> /dev/null)
ifdef DOCKER_COMPOSE
    DC = docker compose
else
    DC = docker-compose
endif

help: ## Mostrar esta ajuda
	@echo "Comandos disponíveis:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

setup: ## Setup inicial do projeto
	@chmod +x setup-docker.sh
	@./setup-docker.sh

build: ## Build das imagens Docker
	$(DC) build

up: ## Iniciar containers
	$(DC) up -d

down: ## Parar containers
	$(DC) down

restart: ## Reiniciar containers
	$(DC) restart

logs: ## Ver logs dos containers
	$(DC) logs -f

logs-app: ## Ver logs do container app
	$(DC) logs -f app

logs-nginx: ## Ver logs do nginx
	$(DC) logs -f nginx

ps: ## Status dos containers
	$(DC) ps

shell: ## Acessar shell do container app
	$(DC) exec app bash

composer-install: ## Instalar dependências composer
	$(DC) exec app composer install

composer-update: ## Atualizar dependências composer
	$(DC) exec app composer update

artisan: ## Executar comando artisan (use: make artisan CMD="migrate")
	$(DC) exec app php artisan $(CMD)

migrate: ## Executar migrations
	$(DC) exec app php artisan migrate

migrate-fresh: ## Migrations fresh (CUIDADO: apaga dados!)
	$(DC) exec app php artisan migrate:fresh

seed: ## Executar seeders
	$(DC) exec app php artisan db:seed

fresh-seed: ## Fresh + seed
	$(DC) exec app php artisan migrate:fresh --seed

cache-clear: ## Limpar todos os caches
	$(DC) exec app php artisan cache:clear
	$(DC) exec app php artisan config:clear
	$(DC) exec app php artisan route:clear
	$(DC) exec app php artisan view:clear

cache-optimize: ## Otimizar caches (produção)
	$(DC) exec app php artisan config:cache
	$(DC) exec app php artisan route:cache
	$(DC) exec app php artisan view:cache
	$(DC) exec app composer dump-autoload -o

test: ## Executar testes
	$(DC) exec app php artisan test

clean: ## Limpar containers, volumes e imagens
	$(DC) down -v
	docker system prune -f

rebuild: ## Rebuild completo (down + build + up)
	$(DC) down
	$(DC) build --no-cache
	$(DC) up -d

permissions: ## Ajustar permissões dos diretórios
	sudo chown -R $$USER:$$USER .
	chmod -R 775 storage bootstrap/cache

install: setup ## Alias para setup

start: up ## Alias para up

stop: down ## Alias para down
