FROM php:8.2-apache

# Pacotes do sistema e extensões PHP necessárias para Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libpq-dev libzip-dev libonig-dev libxml2-dev unzip git curl vim \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mysqli sockets pdo_pgsql zip bcmath mbstring opcache

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Diretório de trabalho
WORKDIR /var/www/html

# Copia apenas Composer files para usar cache
COPY composer.json composer.lock ./

# Instala dependências do Laravel (com log detalhado)
RUN composer install --no-dev --optimize-autoloader --no-interaction --verbose

# Copia o restante do projeto
COPY . .

# Permissões corretas para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Cron
COPY crontab.txt /var/spool/cron/crontabs/root
RUN chmod 600 /var/spool/cron/crontabs/root || true && crontab /var/spool/cron/crontabs/root || true

# Apache
RUN a2enmod rewrite
RUN echo "DocumentRoot /var/www/html/public" > /etc/apache2/sites-available/000-default.conf && \
    echo "<Directory /var/www/html/public>" >> /etc/apache2/sites-available/000-default.conf && \
    echo "    AllowOverride All" >> /etc/apache2/sites-available/000-default.conf && \
    echo "    Require all granted" >> /etc/apache2/sites-available/000-default.conf && \
    echo "</Directory>" >> /etc/apache2/sites-available/000-default.conf

# PHP
RUN echo "upload_max_filesize = 50M" >> /usr/local/etc/php/php.ini && \
    echo "post_max_size = 50M" >> /usr/local/etc/php/php.ini

# Timezone
RUN ln -sf /usr/share/zoneinfo/America/Sao_Paulo /etc/localtime && echo "America/Sao_Paulo" > /etc/timezone

# EntryPoint
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

# Porta
EXPOSE 80

CMD ["apache2-foreground"]
