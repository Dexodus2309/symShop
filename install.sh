#!/bin/sh
cp .env.dist .env
docker-compose up -d --build
docker-compose exec php-cli zsh
composer install
bin/console doctrine:migrations:migrate --no-interaction
exit
echo Click on the link: http://0.0.0.0:8001