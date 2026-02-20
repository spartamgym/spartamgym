FROM php:8.2-cli

# Actualizar e instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    libmariadb-dev \
    wget \
    && docker-php-ext-install \
    pdo_mysql \
    intl \
    opcache \
    zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalar Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash \
    && mv /root/.symfony5/bin/symfony /usr/bin/symfony

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Exponer el puerto
EXPOSE 80

# Variable de entorno para habilitar concurrencia en el servidor de desarrollo de PHP
ENV PHP_CLI_SERVER_WORKERS=5

# Comando para el servidor de Symfony
CMD ["symfony", "server:start", "--port=80", "--no-tls", "--allow-http", "--allow-all-ip"]
