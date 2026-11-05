#!/bin/bash

set -e

cd /var/www

# 1. Instalar dependencias de PHP
if [ -f composer.json ] && [ -f composer.lock ]; then

    COMPOSER_HASH=$(sha256sum composer.json composer.lock | sha256sum | awk '{print $1}')

    if [ ! -f vendor/.composer-lock-hash ] || \
       [ "$(cat vendor/.composer-lock-hash)" != "$COMPOSER_HASH" ]; then

        echo "Instalando o actualizando dependencias de Composer..."

        composer install \
            --no-interaction \
            --prefer-dist \
            --no-progress \
            --optimize-autoloader

        echo "$COMPOSER_HASH" > vendor/.composer-lock-hash
    else
        echo "Las dependencias de Composer están actualizadas."
    fi

else
    echo "ERROR: No se encontraron composer.json o composer.lock."
    exit 1
fi


# 2. Instalar dependencias de Node.js
if [ -f package.json ] && [ -f package-lock.json ]; then

    NPM_HASH=$(sha256sum package.json package-lock.json | sha256sum | awk '{print $1}')

    if [ ! -f node_modules/.package-lock-hash ] || \
       [ "$(cat node_modules/.package-lock-hash)" != "$NPM_HASH" ]; then

        echo "Instalando o actualizando dependencias de NPM..."

        npm ci --no-audit --no-fund

        echo "$NPM_HASH" > node_modules/.package-lock-hash
    else
        echo "Las dependencias de NPM están actualizadas."
    fi
else
    echo "ERROR: No se encontraron package.json o package-lock.json."
    exit 1
fi


# 3. Preparar directorios y permisos
echo "Verificando estructura de carpetas y permisos..."

mkdir -p storage/framework/views \
         storage/framework/cache \
         storage/framework/sessions \
         storage/logs \
         bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache


# 4. Esperar a PostgreSQL
echo "Esperando conexión con PostgreSQL..."
until php artisan db:show > /dev/null 2>&1; do
    sleep 2
done
echo "PostgreSQL está disponible."


# 5. Ejecutar migraciones
echo "Ejecutando migraciones..."
php artisan migrate --force


# 6. Ejecutar seeders una sola vez
echo "Verificando seeders de datos..."
php artisan db:seed --force


# 7. Crear enlace simbólico de Storage
if [ ! -L public/storage ]; then
    echo "Creando enlace simbólico de Storage..."
    php artisan storage:link
fi


# 8. Compilar assets
echo "Compilando assets con Vite..."
npm run build


# 9. Iniciar PHP-FPM
echo "Iniciando servidor PHP-FPM..."
exec php-fpm