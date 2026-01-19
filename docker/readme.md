## PROD

docker compose -f docker/docker-compose.yaml build
docker compose -f docker/docker-compose.yaml -p sc22 up -d
## ONCE
docker compose -f docker/docker-compose.yaml -p sc22 exec -u www-data sc_php php artisan key:generate --ansi
docker compose -f docker/docker-compose.yaml -p sc22 exec -u www-data sc_php php artisan migrate --graceful --ansi

## DEV

## DEV

docker compose -f docker/docker-compose-dev.yaml build
--no-cache

docker compose -f docker/docker-compose-dev.yaml -p sc13 up -d

## ONCE

docker compose -f docker/docker-compose-dev.yaml -p sc13 exec -u www-data sc_php php artisan key:generate --ansi

docker compose -f docker/docker-compose-dev.yaml -p sc13 exec -u www-data sc_php php artisan migrate --graceful --ansi

docker compose -f docker/docker-compose-dev.yaml -p sc13 exec -u www-data sc_php php artisan config:cache
docker compose -f docker/docker-compose-dev.yaml -p sc13 exec -u www-data sc_php php artisan route:cache
docker compose -f docker/docker-compose-dev.yaml -p sc13 exec -u www-data sc_php php artisan view:cache

####

docker compose -f docker/docker-compose-dev.yaml -p sc13 -f docker/docker-compose-dev.yaml config

####

docker compose -f docker/docker-compose-dev.yaml -p sc13 exec -u www-data sc_php php artisan 

####

DEBUG
docker compose -f docker/docker-compose-dev.yaml -p sc13 exec sc_php php artisan tinker
docker compose -f docker/docker-compose-dev.yaml -p sc13 exec sc_php php -r 'echo "hi\n";'
docker compose -f docker/docker-compose-dev.yaml -p sc13 exec sc_php ls -la /app/storage /app/storage/logs
docker compose -f docker/docker-compose-dev.yaml -p sc13 exec sc_php ls -la /app
