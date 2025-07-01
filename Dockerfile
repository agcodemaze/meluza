# Usando a imagem oficial do PHP 8.1 com Apache 
FROM php:8.1-apache

# Atualiza pacotes e instala extensões necessárias
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libpq-dev \  
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mysqli

# Instala o PDO para PostgreSQL (pdo_pgsql) 
RUN docker-php-ext-install pdo_pgsql

# Instala pacotes necessários (vim, cron, curl)
RUN apt-get update && apt-get install -y vim cron curl unzip git

# Baixar o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instala o PHP-JWT via Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer require firebase/php-jwt

RUN composer require aws/aws-sdk-php
RUN composer require intervention/image

# Copia os arquivos do repositório para a pasta do Apache
COPY . /var/www/html/

# Define permissões corretas para o Apache
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Copia e ativa a crontab
COPY crontab.txt /var/spool/cron/crontabs/root
RUN chmod 600 /var/spool/cron/crontabs/root && crontab /var/spool/cron/crontabs/root

# Instala sockets para o email funcionar  
RUN docker-php-ext-install sockets

# Habilita o módulo de reescrita do Apache
RUN a2enmod rewrite

# Adicionando configurações diretamente no php.ini
RUN echo "upload_max_filesize = 50M" >> /usr/local/etc/php/php.ini && \
    echo "post_max_size = 50M" >> /usr/local/etc/php/php.ini

# Configura o Apache para servir a pasta /var/www/html
RUN echo "DocumentRoot /var/www/html" > /etc/apache2/sites-available/000-default.conf && \
    echo "<Directory /var/www/html>" >> /etc/apache2/sites-available/000-default.conf && \
    echo "    AllowOverride All" >> /etc/apache2/sites-available/000-default.conf && \
    echo "    Require all granted" >> /etc/apache2/sites-available/000-default.conf && \
    echo "</Directory>" >> /etc/apache2/sites-available/000-default.conf

# Expõe a porta 80
EXPOSE 80

# Configura o TZ 
RUN ln -sf /usr/share/zoneinfo/America/Sao_Paulo /etc/localtime && \
    echo "America/Sao_Paulo" > /etc/timezone

# Inicia o cron e o Apache no foreground
CMD ["/var/www/html/entrypoint.sh"]