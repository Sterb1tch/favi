#!/bin/bash

echo '\e[1;32mStarting project...\e[1;m'
sh ./dev/down.sh

cp ./docker/local/compose.yml.dist ./compose.yml

docker compose up -d --build --force-recreate
echo '\e[1;32m-> Done\e[1;m'

echo '\e[1;32mInstalling composer...\e[1;m'
docker compose exec -i php composer install
echo '\e[1;32m-> Done\e[1;m'

echo '\e[1;32mCache cleaning and warmup...\e[1;m'
docker compose exec -i php php bin/console cache:clear
docker compose exec -i php php bin/console cache:warmup
echo '\e[1;32m-> Done => INSTALL COMPLETED\e[1;m'
