#!/bin/env bash

if [ ! -f .env.local ]; then
    cp .env.local.dist .env.local
fi

docker compose up -d --build --force-recreate
docker compose exec -i php composer install

docker compose exec -i php bin/console cache:clear
docker compose exec -i php bin/console cache:warmup