# Usando a imagem oficial do PHP 8.1 com Apache
FROM php:8.1-apache

# Atualiza pacotes e instala extensões necessárias
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libpq-dev \
    vim cron curl unzip git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mysqli sockets pdo_pgsql

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Define diretório de trabalho
WORKDIR /var/www/html

# Copia apenas os arquivos do Composer primeiro para aproveitar cache
COPY composer.json composer.lock* ./

# Instala dependências PHP com o Composer
RUN composer install

# Copia o restante dos arquivos do projeto para o container
COPY . .

# Define permissões corretas para o Apache
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Copia e ativa a crontab, se existir
COPY crontab.txt /var/spool/cron/crontabs/root
RUN chmod 600 /var/spool/cron/crontabs/root && \
    crontab /var/spool/cron/crontabs/root

# Habilita o módulo rewrite do Apache
RUN a2enmod rewrite

# Ajusta configurações do php.ini
RUN echo "upload_max_filesize = 50M" >> /usr/local/etc/php/php.ini && \
    echo "post_max_size = 50M" >> /usr/local/etc/php/php.ini

# Configura o Apache para servir a pasta /var/www/html
RUN echo "DocumentRoot /var/www/html" > /etc/apache2/sites-available/000-default.conf && \
    echo "<Directory /var/www/html>" >> /etc/apache2/sites-available/000-default.conf && \
    echo "    AllowOverride All" >> /etc/apache2/sites-available/000-default.conf && \
    echo "    Require all granted" >> /etc/apache2/sites-available/000-default.conf && \
    echo "</Directory>" >> /etc/apache2/sites-available/000-default.conf

# Configura o timezone
RUN ln -sf /usr/share/zoneinfo/America/Sao_Paulo /etc/localtime && \
    echo "America/Sao_Paulo" > /etc/timezone

# Expõe a porta padrão do Apache
EXPOSE 80

# Inicia o Apache no foreground
CMD ["apache2-foreground"]
