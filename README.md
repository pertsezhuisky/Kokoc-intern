# KokocTech Intern — PHP API

Учебный REST API на PHP с использованием Slim Framework, MySQL и Docker.

Проект реализует регистрацию пользователей и работу с задачами.

Технологии
- PHP 8.2
- Apache
- MySQL 8.0
- Docker / Docker Compose
- Slim Framework 4
- PDO
- Composer
- Postman

# Запуск проекта
1) Клонирование проекта

```
git clone https://github.com/pertsezhuisky/Kokoc-intern
cd Kokoc-intern
```

2) Запуск проекта<br>

Для сборки образов и запуска контейнеров выполнить:
```
docker compose up -d --build
```
Проверить запущенные контейнеры:
```
docker compose ps
```
Ожидаются два контейнера:
```
php_app
mysql_db
```
PHP/Apache будет доступен по адресу:

```
http://localhost:8080
```

3. Проверка подключения к MySQL

Подключиться к базе данных внутри Docker:
```
docker exec -it mysql_db mysql -uapp -papp app
```
После подключения можно проверить таблицы:
```
SHOW TABLES;
```
Ожидаемый результат:
```
+---------------+
| Tables_in_app |
+---------------+
| tasks         |
| users         |
+---------------+
```

4. Пересоздание базы данных

SQL-файлы init.sql и seed.sql выполняются MySQL автоматически только при первоначальном создании базы данных.

Если необходимо полностью пересоздать БД вместе с тестовыми данными:
```
docker compose down -v
docker compose up -d --build
```
Внимание: ```-v``` удаляет ```Docker volume mysql_data```, поэтому существующие данные будут потеряны.

# Задание №1 — Docker и Apache
<b>С чем столкнулся</b>

При создании контейнера в качестве директории приложения использовалась src.

При попытке открыть index.php возникала ошибка 404 Not Found, поскольку Apache искал файл по адресу:
```
/var/www/html/index.php
```
При этом фактическое расположение файла было:
```
src/public/index.php
```
<b>Как решил</b>

Первый вариант решения — указать директорию public в docker-compose.yaml:
```
volumes:
  - ./src/public:/var/www/html
```
В таком случае index.php становится доступен непосредственно из /var/www/html.
Однако этот подход скрывает внутреннюю структуру приложения.

<b>Более комплексное решение</b>

В Dockerfile был изменён Apache DocumentRoot:
```
FROM php:8.2-apache

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf
```
Теперь структура внутри контейнера сохраняется:
```
/var/www/html/
├── connections/
├── controllers/
├── repositories/
└── public/
    └── index.php
```
а Apache использует:
```
/var/www/html/public
```
как DocumentRoot.

Это предпочтительный подход, поскольку через веб-сервер доступна только публичная директория public, а внутренние файлы приложения напрямую не раздаются.

# Задание №2 — MySQL и SQL

SQL был разделён на несколько файлов:

- init.sql — создание структуры базы данных;
- seed.sql — заполнение базы тестовыми данными;
- query.sql — дополнительные SQL-запросы для работы с базой.

Структура базы данных

Таблица users:
```
id
name
email
password
phone_number
register_date
is_active
```
Таблица tasks:
```
id
title
description
user_id
created_at
```

Между таблицами установлена связь:
```
users.id → tasks.user_id
```
При удалении пользователя его задачи удаляются автоматически благодаря:
```
ON DELETE CASCADE
```
<b>Инициализация базы в Docker</b>

В docker-compose.yaml SQL-файлы подключаются к стандартной директории инициализации MySQL:
```
volumes:
  - mysql_data:/var/lib/mysql
  - ./database/init.sql:/docker-entrypoint-initdb.d/01-init.sql
  - ./database/seed.sql:/docker-entrypoint-initdb.d/02-seed.sql
```
После запуска контейнера MySQL автоматически:

- создаёт таблицы;
- добавляет тестовые данные.

Проверка
```
docker exec -it mysql_db mysql -uapp -papp app
```
Затем:
```
SHOW TABLES;
```
Результат:
```
+---------------+
| Tables_in_app |
+---------------+
| tasks         |
| users         |
+---------------+
```
Трудностей при выполнении задания не возникло.

# Задание №3 — API и структура приложения

Для реализации API был выбран Slim Framework 4.

Он используется для маршрутизации запросов, а взаимодействие с MySQL выполняется через PDO.

Архитектура

Приложение разделено на несколько частей:
```
HTTP Request
     ↓
   Slim
     ↓
 Controller
     ↓
 Repository
     ↓
    PDO
     ↓
   MySQL
```
DatabaseConnection отвечает только за создание подключения к MySQL через PDO.

Controller отвечает за:

- получение HTTP-запроса;
- валидацию входных данных;
- вызов необходимых методов;
- формирование JSON-ответа;
- HTTP-коды ответа.

Repository отвечает за SQL-запросы и получение/изменение данных в БД.

Для запросов используются параметризованные выражения PDO, что позволяет избежать SQL-инъекций.

Структура проекта получилась следующая:
```
src/
│
├── composer.json
├── composer.lock
│
├── connections/
│   └── DatabaseConnection.php
│       └── Подключение к базе данных через PDO
│
├── controllers/
│   ├── TaskController.php
│   │   └── Обработка запросов, связанных с задачами
│   │
│   └── UserController.php
│       └── Обработка запросов, связанных с пользователями
│
├── public/
│   ├── .htaccess
│   │   └── Настройка Apache и перенаправление запросов в index.php
│   │
│   └── index.php
│       └── Точка входа приложения и настройка маршрутов API
│
└── repositories/
    └── TaskRepository.php
        └── Работа с задачами в базе данных: получение, создание
```
# Реализованные endpoints

<h3>POST /api/users - Регистрация пользователя.</h3>

<i>Request</i>
```
{
    "email": "test@example.com",
    "password": "password123",
    "name": "Test User"
}
```
<b>Реализованная безопасность</b>

Email проверяется с помощью регулярного выражения:
```
preg_match(
    '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
    $email
);
```
Пароль не сохраняется в открытом виде:
```
$passwordHash = password_hash($password, PASSWORD_DEFAULT);
```
В базу сохраняется только хэш. Пароль также не возвращается в API-ответе.

<h3>GET /api/users/{id}/tasks - Возвращает задачи указанного пользователя.</h3>

<i>Request:</i>
```
GET http://localhost:8080/api/users/1/tasks
```
<i>Response:</i>
```
{
    "user_id": 1,
    "tasks": [
        {
            "id": 1,
            "title": "Implement authentication",
            "description": "Implement user authentication",
            "user_id": 1,
            "created_at": "2026-08-21 10:00:00"
        }
    ],
    "count": 1
}
```
Для запросов к БД используются подобные запросы:
```
$stmt = $this->pdo->prepare(
    'SELECT ... FROM tasks WHERE user_id = :user_id'
);
```

<h3>POST /api/tasks - Создание задачи.</h3>

<i>Request</i>
```
{
    "user_id": 1,
    "title": "Test task",
    "description": "Task description"
}
```
<i>Response</i>
```
{
    "message": "Задача создана",
    "task": {
        "id": 9,
        "title": "Test task",
        "description": "Task description",
        "user_id": 1,
        "created_at": "2026-08-21 10:30:00"
    }
}
```

<b>Обработка ошибок</b>

В коде используется try-catch для обработки ошибок базы данных.

Основные HTTP-коды:

- 200 - Успешный запрос
- 201 - Ресурс создан
- 400 - Некорректные входные данные
- 404 - Пользователь не найден
- 500 - Ошибка базы данных

При ошибке клиент получает JSON с понятным сообщением.

# Задание №4 - Тестирование с помощью postman

Для проекта подготовлена коллекция:
```
collection.json
```
Проверены следующие запросы:

<b>POST /api/users</b>

Проверки:

- корректная регистрация;
- некорректный email;
- отсутствие password;
- регистрация с уже существующим email.

<b>GET /api/users/{id}/tasks</b>

Проверки:

- получение задач существующего пользователя;
- пользователь, которого нет в БД;
- получение списка задач;
- некорректный id пользователя.


<b>POST /api/tasks</b>

Проверки:
- корректное создание задачи;
- несуществующий user_id;
- отсутствие обязательного title;
- пустые обязательные поля.
