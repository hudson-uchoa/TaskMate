#!/bin/bash

until pg_isready -h db -p 5432 -U postgres; do
  echo "Aguardando banco de dados..."
  sleep 2
done

echo "Rodando migrations..."
composer migrate

echo "Inicializando o servidor"
php -S 0.0.0.0:8000 -t public
