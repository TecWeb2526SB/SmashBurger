# Immagine ufficiale PHP 8.1 con Apache, stessa versione del server di consegna
FROM php:8.1-apache

# Aggiorniamo i pacchetti del sistema operativo e installiamo le dipendenze per MariaDB/MySQL
RUN apt-get update && apt-get install -y \
    libmariadb-dev \
    default-mysql-client \
    # Installiamo le estensioni PHP necessarie per interagire con MariaDB/MySQL
    && docker-php-ext-install mysqli pdo pdo_mysql \
    # Pulizia della cache di apt per ridurre la dimensione dell'immagine finale
    && rm -rf /var/lib/apt/lists/*

# Moduli Apache usati dal sito: riscrittura degli indirizzi, header di sicurezza,
# compressione e scadenze delle risorse statiche
RUN a2enmod rewrite headers deflate expires

# Impostiamo la directory di lavoro all'interno del container
WORKDIR /var/www/html
