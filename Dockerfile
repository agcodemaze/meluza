# Usando a imagem oficial do PHP 8.1 com Apache
FROM php:8.1-apache

# Atualiza pacotes e instala extensões necessárias
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libpq-dev vim cron curl unzip git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mysqli sockets pdo_pgsql

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Define diretório de trabalho
WORKDIR /var/www/html

# Copia apenas arquivos do Composer e instala dependências (para aproveitar cache)
COPY composer.json composer.lock ./
RUN composer install --optimize-autoloader --no-dev

# Copia o restante do projeto
COPY . .

# Permissões corretas para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copia e ativa a crontab, se existir
COPY crontab.txt /var/spool/cron/crontabs/root
RUN chmod 600 /var/spool/cron/crontabs/root || true && \
    crontab /var/spool/cron/crontabs/root || true

# Habilita o módulo rewrite do Apache
RUN a2enmod rewrite

# Ajusta configurações do php.ini
RUN echo "upload_max_filesize = 50M" >> /usr/local/etc/php/php.ini && \
    echo "post_max_size = 50M" >> /usr/local/etc/php/php.ini

# Apache serve a pasta /public do Laravel
RUN echo "DocumentRoot /var/www/html/public" > /etc/apache2/sites-available/000-default.conf && \
    echo "<Directory /var/www/html/public>" >> /etc/apache2/sites-available/000-default.conf && \
    echo "    AllowOverride All" >> /etc/apache2/sites-available/000-default.conf && \
    echo "    Require all granted" >> /etc/apache2/sites-available/000-default.conf && \
    echo "</Directory>" >> /etc/apache2/sites-available/000-default.conf

# Configura timezone
RUN ln -sf /usr/share/zoneinfo/America/Sao_Paulo /etc/localtime && \
    echo "America/Sao_Paulo" > /etc/timezone

# Expõe a porta padrão do Apache
EXPOSE 80

# Copia entrypoint para rodar Laravel automaticamente
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

# Comando final do Apache
CMD ["apache2-foreground"]
