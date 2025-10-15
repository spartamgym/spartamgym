FROM mcr.microsoft.com/devcontainers/php:1-8.2-bookworm

RUN apt-get update && export DEBIAN_FRONTEND=noninteractive \
    && apt-get upgrade -y

# Install MariaDB client
RUN apt-get update && export DEBIAN_FRONTEND=noninteractive \
    && apt-get install -y mariadb-client \ 
    && apt-get clean -y && rm -rf /var/lib/apt/lists/*

# Install php-mysql driver
RUN apt-get update && apt-get install -y \
    libicu-dev \
    zlib1g-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install \
    mysqli \
    pdo_mysql \
    intl \
    zip \
    && rm -rf /var/lib/apt/lists/*

# Install Node.js (latest version) and npm
RUN apt-get update && export DEBIAN_FRONTEND=noninteractive \
    && apt-get install -y curl \
    && curl -fsSL https://deb.nodesource.com/setup_current.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean -y && rm -rf /var/lib/apt/lists/*

# Instalación de Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony
# [Optional] Uncomment this section to install additional OS packages.
# RUN apt-get update && export DEBIAN_FRONTEND=noninteractive \
#     && apt-get -y install --no-install-recommends <your-package-list-here>
# [Optional] Uncomment this line to install global node packages.
# RUN su vscode -c "source /usr/local/share/nvm/nvm.sh && npm install -g <your-package-here>" 2>&1


COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

COPY 99-custom.ini /usr/local/etc/php/conf.d/

RUN a2ensite 000-default.conf

WORKDIR /workspaces/spartamgym
RUN chmod -R 777 /workspaces/spartamgym
RUN chown -R www-data:www-data /workspaces/spartamgym


