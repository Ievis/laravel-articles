### Инструкция по запуску проекта:

#### 1. Установить зависимости

```
composer install
```

#### 2. Поднять контейнеры

```
docker-compose up -d
```

#### 3. Скопировать .env.example-файл в .env-файл

```
docker exec -it mamr_app cp /var/www/.env.example /var/www/.env
```

#### 4. Установить необходимые права для файлов проекта

```
docker exec -it mamr_app chmod -R 777 /var/www
```

#### 5. Сгенерировать ключ приложения, кэшировать маршруты, сгенерировать jwt-ключ приложения

```
docker exec -it mamr_app php /var/www/artisan key:generate
docker exec -it mamr_app php /var/www/artisan route:cache
docker exec -it mamr_app php /var/www/artisan jwt:secret --force
```


#### 6. Выполнить миграцию БД

```
docker exec -it mamr_app php /var/www/artisan migrate --force
```

#### 7. Запустить тесты

```
docker exec -it mamr_app php /var/www/artisan test
```

#### 8. Выполнить seed'ы

```
docker exec -it mamr_app php /var/www/artisan db:seed --class=UserSeeder --force
docker exec -it mamr_app php /var/www/artisan db:seed --class=CategorySeeder --force
docker exec -it mamr_app php /var/www/artisan db:seed --class=ArticleSeeder --force
```

#### Готово
```
localhost:8000 - точка входа в приложение
```
