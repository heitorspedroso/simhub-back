#!/bin/bash

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  Simhub Backend - Setup Docker${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Verificar se o Docker está instalado
if ! command -v docker &> /dev/null; then
    echo -e "${RED}❌ Docker não está instalado. Por favor, instale o Docker primeiro.${NC}"
    exit 1
fi

if ! command -v docker-compose &> /dev/null && ! docker compose version &> /dev/null 2>&1; then
    echo -e "${RED}❌ Docker Compose não está instalado. Por favor, instale o Docker Compose primeiro.${NC}"
    exit 1
fi

# Verificar se existe arquivo .env
if [ ! -f .env ]; then
    echo -e "${YELLOW}📝 Arquivo .env não encontrado. Copiando .env.docker...${NC}"
    cp .env.docker .env
    echo -e "${GREEN}✅ Arquivo .env criado!${NC}"
else
    echo -e "${GREEN}✅ Arquivo .env já existe.${NC}"
fi

# Criar diretórios necessários
echo -e "${YELLOW}📁 Criando diretórios necessários...${NC}"
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache
echo -e "${GREEN}✅ Diretórios criados!${NC}"

# Ajustar permissões
echo -e "${YELLOW}🔐 Ajustando permissões...${NC}"
chmod -R 775 storage bootstrap/cache
echo -e "${GREEN}✅ Permissões ajustadas!${NC}"

# Build das imagens
echo -e "${YELLOW}🏗️  Fazendo build das imagens Docker...${NC}"
if docker compose version &> /dev/null 2>&1; then
    docker compose build
else
    docker-compose build
fi

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Build concluído com sucesso!${NC}"
else
    echo -e "${RED}❌ Erro ao fazer build das imagens.${NC}"
    exit 1
fi

# Iniciar containers
echo -e "${YELLOW}🚀 Iniciando containers...${NC}"
if docker compose version &> /dev/null 2>&1; then
    docker compose up -d
else
    docker-compose up -d
fi

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Containers iniciados!${NC}"
else
    echo -e "${RED}❌ Erro ao iniciar containers.${NC}"
    exit 1
fi

# Aguardar containers iniciarem
echo -e "${YELLOW}⏳ Aguardando containers iniciarem...${NC}"
sleep 5

# Instalar dependências do Composer
echo -e "${YELLOW}📦 Instalando dependências do Composer...${NC}"
if docker compose version &> /dev/null 2>&1; then
    docker compose exec -T app composer install
else
    docker-compose exec -T app composer install
fi

# Gerar chave da aplicação se necessário
echo -e "${YELLOW}🔑 Verificando chave da aplicação...${NC}"
if docker compose version &> /dev/null 2>&1; then
    docker compose exec -T app php artisan key:generate --ansi
else
    docker-compose exec -T app php artisan key:generate --ansi
fi

# Executar migrations (opcional - descomentar se necessário)
# echo -e "${YELLOW}🗃️  Executando migrations...${NC}"
# if docker compose version &> /dev/null 2>&1; then
#     docker compose exec -T app php artisan migrate --force
# else
#     docker-compose exec -T app php artisan migrate --force
# fi

# Limpar cache
echo -e "${YELLOW}🧹 Limpando cache...${NC}"
if docker compose version &> /dev/null 2>&1; then
    docker compose exec -T app php artisan config:clear
    docker compose exec -T app php artisan cache:clear
    docker compose exec -T app php artisan route:clear
    docker compose exec -T app php artisan view:clear
else
    docker-compose exec -T app php artisan config:clear
    docker-compose exec -T app php artisan cache:clear
    docker-compose exec -T app php artisan route:clear
    docker-compose exec -T app php artisan view:clear
fi

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  ✅ Setup concluído com sucesso!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "📍 Aplicação disponível em: ${YELLOW}http://localhost:8500${NC}"
echo ""
echo -e "Comandos úteis:"
echo -e "  ${YELLOW}docker compose ps${NC}              - Ver status dos containers"
echo -e "  ${YELLOW}docker compose logs -f app${NC}    - Ver logs da aplicação"
echo -e "  ${YELLOW}docker compose down${NC}            - Parar containers"
echo -e "  ${YELLOW}docker compose exec app bash${NC}  - Acessar shell do container"
echo ""
