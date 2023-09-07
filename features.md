### Дедлайн - 10 сентября

### Стек и архитектура:

```
1. Две отдельные директории для фронта (app) и бека (api).
2. Настроенный nginx для фронта и бека.
3. MySQL v.latest - основное хранилище данных.
4. MySQL workbench для просмотра данных.
5. Redis v.latest - кэш.
6. PHP v.8.1.x.
7. Laravel v.latest.
8. CSS-фреймворк - tailwind v.latest.
9. JS-библиотека - axios v.latest.
8. Завернуть всё это в докер-контейнер.
```

### API:

```
1. CRUD для статей.
2. CRUD для категорий статей.
3. CRUD для пользователей.
4. Разграничение прав пользователей по ролям (главный админ, админ).
5. Seed'ы БД.
6. Алгоритм фильтрации статей по атрибуту number_in_category.
7. Алгоритм фильтрации категорий по атрибуту number.
8. Кэширование статей для пользователей.
```

### БД:

```
articles:
    name               - varchar(255)
    slug               - varchar(255)
    category_id        - bigint
    image              - varchar(255)
    is_active          - tinyint
    number_in_category - bigint 
    
categories:
    name               - varchar(255)
    is_active          - tinyint
    number             - bigint
    
users:
    email              - varchar(255)
    password           - tinyint
    is_active          - bigint
```

### Front:

#### HTML/CSS:

```
1. Вёрстка списка статей от лица гостя.
2. Вёрстка списка статей от лица администратора.
3. Вёрстка формы входа в админ-панель.
4. Вёрстка формы добавления/редактирования статьи.
5. Вёрстка формы добавления/редактирования администратора.
```

#### JS:

```
1. Использование библиотеки для чекбоксов (https://abpetkov.github.io/switchery/).
2. Использование библиотеки для сортировки записей (https://sortablejs.github.io/Sortable/).
3. Асинхронный запрос для формы добавления/редактирования/удаления статьи.
4. Асинхронный запрос для формы добавления/редактирования/удаления категории.
5. Асинхронный запрос для формы добавления/редактирования/удаления администратора.
6. Подключение библиотек из локальной среды без Vite.
```

В CRUD для статей пагинация (параметр `page`), фильтрация по аттрибуту порядка и любым другим атрибутам статей.
Выложить проект на github, отправить ссылку рекрутёру.
В файле README.md написать инструкцию по разворачиванию проекта.