#!/bin/bash

# Espera o MySQL estar pronto (pode ajustar o tempo se necessário)
echo "Aguardando o banco de dados..."
sleep 10

echo "Executando comandos Laravel..."

# Roda as migrações com force para produção
php artisan migrate --force

# Gera cache de config e rotas
php artisan config:clear
php artisan config:cache
php artisan route:cache

# Gera link de storage (se não existir)
if [ ! -L "/var/www/html/public/storage" ]; then
    php artisan storage:link
fi

echo "Laravel pronto! Iniciando Apache..."

# Executa o comando original do container (Apache)
exec "$@"
