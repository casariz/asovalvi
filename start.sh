#!/bin/bash

# Script de inicio para Laravel

# Ejecutar migraciones
php artisan migrate --force

# Iniciar el servidor de Laravel
php artisan serve --host=0.0.0.0 --port=8000