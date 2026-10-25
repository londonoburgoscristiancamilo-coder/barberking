#!/bin/bash

# Instalar dependencias de Node si aún no están instaladas
if [ ! -d "node_modules" ]; then
    npm install
fi

# Iniciar Vite (npm run dev) en segundo plano con el flag --host para que sea accesible
npm run dev -- --host &

# Iniciar el proceso principal de PHP-FPM
exec php-fpm