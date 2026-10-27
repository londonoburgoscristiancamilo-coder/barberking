#!/bin/bash

# Instalar dependencias de Node si aún no están instaladas
if [ ! -d "node_modules" ]; then
    echo "Instalando dependencias de Node por primera vez..."
    npm install
fi

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

# Iniciar el servidor local en desarrollo (npm run dev) en segundo plano con el flag --host para que sea accesible
echo "Levantando servidor de desarrollo..."
npm run dev -- --host &

# Iniciar el proceso principal de PHP-FPM
exec php-fpm