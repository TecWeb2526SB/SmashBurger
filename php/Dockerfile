# Usiamo PHP 8.3 con FPM (FastCGI Process Manager) basato su Alpine Linux
FROM php:8.3-fpm-alpine

# Aggiorniamo i pacchetti e installiamo le dipendenze per PostgreSQL
RUN apk update && apk add --no-cache \
    postgresql-dev \
    # Installiamo e abilitiamo le estensioni PHP per PostgreSQL
    && docker-php-ext-install pdo pdo_pgsql

# Impostiamo la cartella di lavoro di default
WORKDIR /var/www/html