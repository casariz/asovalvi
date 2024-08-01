#!/bin/bash

# Mostrar todos los comandos ejecutados
set -e

# Actualizar los paquetes e instalar dependencias necesarias
sudo apt-get update
sudo apt-get install -y wget php-cli php-zip unzip

# Descargar el instalador de Composer
wget -O composer-setup.php https://getcomposer.org/installer

# Verificar la firma del instalador (opcional pero recomendado)
EXPECTED_SIGNATURE=$(wget -q -O - https://composer.github.io/installer.sig)
ACTUAL_SIGNATURE=$(php -r "echo hash_file('SHA384', 'composer-setup.php');")

if [ "$EXPECTED_SIGNATURE" != "$ACTUAL_SIGNATURE" ]
then
    echo 'ERROR: La firma del instalador no coincide' >&2
    rm composer-setup.php
    exit 1
fi

# Instalar Composer
php composer-setup.php --install-dir=/usr/local/bin --filename=composer

# Limpiar
rm composer-setup.php

# Verificar la instalación
composer --version

echo 'Composer instalado correctamente'