#!/bin/bash

# Instalar dependencias de PHP si la carpeta vendor no existe
if [ ! -d "vendor" ]; then
    echo "Instalando dependencias de PHP por primera vez..."
    composer install
fi

# Instalar dependencias de Node si aún no están instaladas y ejecutando script necesario para TailwindCSS
if [ ! -d "node_modules" ]; then
    echo "Instalando dependencias de Node por primera vez..."
    npm install
fi

# Crear carpetas temporales de Laravel si Git las ignoró y ajustar permisos
echo "Verificando estructura de carpetas y permisos..."
mkdir -p storage/framework/views storage/framework/cache storage/framework/sessions storage/logs
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Esperar a que la base de datos esté lista antes de migrar
echo "Esperando a que la base de datos esté lista..."
until php artisan db:show > /dev/null 2>&1; do
    sleep 2
done

# Ejecutar migraciones automáticamente si la base de datos está vacía
echo "Verificando y ejecutando migraciones de base de datos..."
php artisan migrate --force

# Ejecutar seeders automáticamente si las tablas están vacías
echo "Verificando y ejecutando seeders de base de datos..."
php artisan db:seed --force

# Crear el enlace simbólico si no existe
if [ ! -d "public/storage" ]; then
    echo "Creando enlace simbólico del storage..."
    php artisan storage:link
fi

# Compilar assets estáticos al iniciar el contenedor
echo "Compilando assets estáticos con Vite..."
npm run build

# Iniciar el proceso principal de PHP-FPM
exec php-fpm