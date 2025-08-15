# Usando PHP 8.2 com Apache
FROM php:8.2-apache

# Atualiza pacotes e instala extensões necessárias
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libpq-dev vim cron curl unzip git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mysqli sockets pdo_pgsql

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copia todo o projeto para o container
COPY . /var/www/html/

# Define diretório de trabalho
WORKDIR /var/www/html

# Instala dependências sem rodar scripts para evitar erro com artisan
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Define permissões corretas para Laravel
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copia e ativa a crontab, se existir
COPY crontab.txt /var/spool/cron/crontabs/root
RUN chmod 600 /var/spool/cron/crontabs/root || true && \
    crontab /var/spool/cron/crontabs/root || true

# Habilita módulo rewrite do Apache
RUN a2enmod rewrite

# Ajusta configurações do php.ini
RUN echo "upload_max_filesize = 50M" >> /usr/local/etc/php/php.ini && \
    echo "post_max_size = 50M" >> /usr/local/etc/php/php.ini

# Configura Apache para servir /var/www/html/public
RUN echo "DocumentRoot /var/www/html/public" > /etc/apache2/sites-available/000-default.conf && \
    echo "<Directory /var/www/html/public>" >> /etc/apache2/sites-available/000-default.conf && \
    echo "    AllowOverride All" >> /etc/apache2/sites-available/000-default.conf && \
    echo "    Require all granted" >> /etc/apache2/sites-available/000-default.conf && \
    echo "</Directory>" >> /etc/apache2/sites-available/000-default.conf

    # Permissões corretas para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
    
# Configura timezone
RUN ln -sf /usr/share/zoneinfo/America/Sao_Paulo /etc/localtime && \
    echo "America/Sao_Paulo" > /etc/timezone

# Expõe porta padrão do Apache
EXPOSE 80

# Gera a chave do Laravel e cacheia config
RUN php artisan key:generate || true && php artisan config:cache || true

# Inicia Apache no foreground
CMD ["apache2-foreground"]
