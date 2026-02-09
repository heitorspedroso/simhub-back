FROM php:8.0-fpm

# Argumentos para user e group ID
ARG USER_ID=1000
ARG GROUP_ID=1000

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    gnupg \
    apt-transport-https \
    ca-certificates \
    wget

# Adicionar repositório da Microsoft para SQL Server
RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/config/debian/11/prod.list > /etc/apt/sources.list.d/mssql-release.list

# Instalar driver ODBC e ferramentas SQL Server
RUN apt-get update \
    && ACCEPT_EULA=Y apt-get install -y \
    msodbcsql17 \
    mssql-tools \
    unixodbc-dev

# Adicionar mssql-tools ao PATH
ENV PATH="$PATH:/opt/mssql-tools/bin"

# Limpar cache do apt
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensões PHP
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Instalar driver SQL Server para PHP (versões compatíveis com PHP 8.0)
RUN pecl install sqlsrv-5.10.1 pdo_sqlsrv-5.10.1 \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Criar grupo e usuário com IDs configuráveis
RUN groupadd -g ${GROUP_ID} laravel \
    && useradd -u ${USER_ID} -g laravel -m -s /bin/bash laravel

# Definir diretório de trabalho
WORKDIR /var/www/html

# Copiar arquivos do projeto
COPY --chown=laravel:laravel . .

# Criar diretórios necessários e ajustar permissões
RUN mkdir -p /var/www/html/storage/framework/cache/data \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/logs \
    /var/www/html/bootstrap/cache \
    && chown -R laravel:laravel /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Mudar para o usuário laravel
USER laravel

# Instalar dependências do Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Expor porta 9000 para PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
