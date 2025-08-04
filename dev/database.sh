#!/bin/env bash

docker compose exec -i php bin/console doctrine:database:drop --force
docker compose exec -i php bin/console doctrine:database:create
docker compose exec -i php bin/console d:d:d --force
docker compose exec -i php bin/console d:d:c
docker compose exec -i php bin/console doctrine:migrations:migrate --no-interaction
