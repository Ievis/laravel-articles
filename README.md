### Инструкция по запуску проекта:

#### 1. Установить зависимости

```
composer install
```

#### 2. Поднять контейнеры

```
docker-compose up -d
```

#### 3. Установить необходимые права для файлов проекта

```
docker exec -it mamr_app chmod -R 777 /var/www
```

#### 4. Выполнить миграцию БД

```
docker exec -it mamr_app php /var/www/artisan migrate
```

#### 5. Выполнить seed'ы

```
1. docker exec -it mamr_app php /var/www/artisan db:seed --class=UserSeeder
2. docker exec -it mamr_app php /var/www/artisan db:seed --class=CategorySeeder
3. docker exec -it mamr_app php /var/www/artisan db:seed --class=ArticleSeeder
```
