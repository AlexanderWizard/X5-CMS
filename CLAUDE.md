# CLAUDE.md — notify_service

Инструкции для Claude Code при работе с этим репозиторием.

---

## Стек

- **Laravel 13.14.0**, PHP 8.3
- **MySQL 5.6** — IP `127.127.126.24:3306`, БД `notify_service`, пользователь `root`, пароль пустой
- **Swagger UI** — пакет `darkaonline/l5-swagger ^11.0`

---

## Команды

```bash
# Запуск dev-сервера
php artisan serve

# Миграции
php artisan migrate
php artisan migrate:fresh

# Генерация Swagger-документации (после изменения аннотаций)
php artisan l5-swagger:generate

# Запуск обработчика очереди вручную
php artisan queue:processed

# Просмотр расписания
php artisan schedule:list

# Запуск планировщика (для теста)
php artisan schedule:run
```

---

## Конфигурация

- Переменные окружения — `.env` в корне
- После изменения `.env` выполнять `php artisan config:clear`

### Ключевые .env-параметры

```
DB_CONNECTION=mysql
DB_HOST=127.127.126.24
DB_PORT=3306
DB_DATABASE=notify_service
DB_USERNAME=root
DB_PASSWORD=
```

---

## Архитектура

### Точка входа и роутинг

`public/index.php` → `bootstrap/app.php`

Маршруты:
- `routes/api.php` — API-маршруты (префикс `/api`)
- `routes/web.php` — веб-маршруты
- `routes/console.php` — Artisan-команды и планировщик

### API

| Метод | URL           | Контроллер                  | Описание                        |
|-------|---------------|-----------------------------|---------------------------------|
| POST  | /api/message  | ApiController@message       | Сохранить сообщение в очередь   |
| GET   | /docs         | l5-swagger SwaggerController | Swagger UI                     |
| GET   | /docs/json    | l5-swagger SwaggerController | OpenAPI JSON-спека             |

### Контроллеры

**`app/Http/Controllers/ApiController.php`**
- Содержит глобальные Swagger-аннотации `#[OA\Info]` и `#[OA\Server]`
- Все новые API-методы добавлять сюда или в отдельные контроллеры с `#[OA\...]` атрибутами

### Модели

**`app/Models/MessageQueue.php`**
- Таблица: `messages_queue`
- `$timestamps = false` (только `created_at`, управляется через `useCurrent()` в БД)
- `$fillable = ['channel', 'body']`
- `is_processed` — **внутреннее системное поле**, не добавлять в `$fillable`, не возвращать в API

---

## База данных

### Таблица `messages_queue`

| Колонка      | Тип          | Default         | Описание                             |
|--------------|--------------|-----------------|--------------------------------------|
| id           | bigint PK    | auto_increment  | первичный ключ                       |
| channel      | varchar(255) | —               | канал доставки (email, sms, push...) |
| body         | text         | —               | тело сообщения                       |
| is_processed | tinyint(1)   | 0               | системное, 0=не обработано, 1=готово |
| created_at   | timestamp    | CURRENT_TIMESTAMP | дата создания записи              |

### Совместимость MySQL 5.6

В `app/Providers/AppServiceProvider.php` прописан фикс для ограничения длины индексов:

```php
Builder::defaultStringLength(191);
```

Без него миграции падают с ошибкой `Specified key was too long; max key length is 767 bytes`.

---

## Swagger

Аннотации пишутся как **PHP-атрибуты** (swagger-php v6):

```php
#[OA\Get(
    path: '/api/example',
    summary: 'Описание',
    tags: ['TagName'],
    responses: [
        new OA\Response(response: 200, description: 'OK')
    ]
)]
```

После изменения аннотаций — обязательно запустить:
```bash
php artisan l5-swagger:generate
```

### Настройки маршрутов Swagger (`config/l5-swagger.php`)

```php
// documentations.default.routes
'api'  => 'docs',       // HTML UI
'docs' => 'docs/json',  // JSON-спека (ассеты: docs/json/asset/{asset})
```

---

## Крон / Планировщик

### Команда `queue:processed`

Файл: `app/Console/Commands/ProcessQueue.php`

- Выбирает до **20** записей где `is_processed = 0`
- Одним запросом выставляет `is_processed = 1`
- Лог: `storage/logs/queue-processed.log`
- Защита от параллельного запуска: `withoutOverlapping()`

### Расписание

Периодичность: **каждые 2 минуты** — задана в `routes/console.php`.

### Системный крон (Linux)

```bash
crontab -e
# Добавить:
* * * * * cd /var/www/laraval.local && php artisan schedule:run >> /dev/null 2>&1
```

> Крон вызывает `schedule:run` каждую минуту — Laravel сам управляет периодичностью задач внутри.

---

## OpenServer (локальная разработка)

- Домен: `laraval.local`
- Конфиг: `.osp/project.ini`
- PHP engine: `PHP-8.3`
- Public dir: `{base_dir}\public`
- Composer: `C:\Users\admin\AppData\Roaming\Composer\composer.phar`
