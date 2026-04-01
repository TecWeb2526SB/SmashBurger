# Usiamo l'immagine ufficiale PHP 8.3 con Apache pre-configurato
FROM php:8.3-apache

# Aggiorniamo i pacchetti del sistema operativo e installiamo le dipendenze per MariaDB/MySQL
RUN apt-get update && apt-get install -y \
    libmariadb-dev \
    default-mysql-client \
    # Installiamo le estensioni PHP necessarie per interagire con MariaDB/MySQL
    && docker-php-ext-install mysqli pdo pdo_mysql \
    # Pulizia della cache di apt per ridurre la dimensione dell'immagine finale
    && rm -rf /var/lib/apt/lists/*

# Abilitiamo i moduli Apache necessari per routing e header di sicurezza
RUN a2enmod rewrite headers

# Impostiamo la directory di lavoro all'interno del container
WORKDIR /var/www/html
