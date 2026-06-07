# CLAUDE.md — notify_service

Инструкции для Claude Code при работе с этим репозиторием.

---

## Стек

- **Laravel 13.14.0**, PHP 8.3.6
- **MySQL 5.6** — IP `127.127.126.24:3306`, БД `notify_service`, пользователь `root`, пароль пустой
- **Filament v4** — админ-панель (`/admin`)
- **l5-swagger** (`darkaonline/l5-swagger ^11.0`) — Swagger UI (`/docs`)
- **OpenServer**, домен `laraval.local`

---

## Команды

```bash
php artisan serve                  # Dev-сервер
php artisan migrate                # Миграции
php artisan migrate:fresh          # Сброс и пересоздание БД
php artisan l5-swagger:generate    # Генерация Swagger (после изменения аннотаций)
php artisan queue:processed        # Запуск обработчика очереди вручную
php artisan schedule:run           # Запуск планировщика (для теста)
php artisan schedule:list          # Просмотр расписания
php artisan config:clear           # Очистка кэша конфига
php artisan view:clear             # Очистка кэша шаблонов
php artisan route:clear            # Очистка кэша маршрутов
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

### URL-карта

| URL | Что | Защита |
|---|---|---|
| `POST /api/message` | Сохранить сообщение в очередь | — |
| `/docs` | Swagger UI | auth:admin |
| `/docs/json` | OpenAPI JSON-спека | auth:admin |
| `/admin` | Dashboard (виджет статистики) | auth:admin |
| `/admin/login` | Форма входа | — |
| `/admin/profile` | Профиль (язык, часовой пояс) | auth:admin |
| `/admin/api/messages` | Список очереди сообщений | auth:admin |
| `/admin/system/users` | Управление пользователями | auth:admin |

---

## Модульная архитектура

Код организован по модулям в `app/Modules/`. Каждый модуль — самодостаточная папка.

```
app/Modules/
├── Api/
│   ├── Models/
│   │   └── MessageQueue.php          # Модель очереди (таблица messages_queue)
│   └── Filament/
│       ├── Resources/
│       │   └── MessageQueueResource.php  # CRUD-ресурс (только чтение)
│       │   └── MessageQueueResource/Pages/ListMessageQueues.php
│       └── Widgets/
│           └── QueueStatsWidget.php  # Виджет: всего / не обработано / обработано
└── System/
    ├── Models/
    │   └── User.php                  # Системный пользователь (таблица users)
    └── Filament/
        └── Resources/
            └── UserResource.php      # CRUD пользователей
            └── UserResource/Pages/{List,Create,Edit}User.php
```

PSR-4 namespace: `App\Modules\*` — автоматически покрывается `"App\\": "app/"` в `composer.json`.

### Регистрация модулей в Filament

`app/Providers/Filament/AdminPanelProvider.php`:
```php
->discoverResources(in: app_path('Modules/Api/Filament/Resources'), for: 'App\Modules\Api\Filament\Resources')
->discoverResources(in: app_path('Modules/System/Filament/Resources'), for: 'App\Modules\System\Filament\Resources')
->discoverWidgets(in: app_path('Modules/Api/Filament/Widgets'), for: 'App\Modules\Api\Filament\Widgets')
->discoverWidgets(in: app_path('Modules/System/Filament/Widgets'), for: 'App\Modules\System\Filament\Widgets')
```

---

## База данных

### Совместимость MySQL 5.6

В `app/Providers/AppServiceProvider.php`:
```php
Builder::defaultStringLength(191);
```
Без этого миграции падают: `Specified key was too long; max key length is 767 bytes`.

**Известные ограничения MySQL 5.6:**
- `php artisan db:table` и `db:show` не работают (нет `generation_expression` в `information_schema`)
- Для просмотра структуры: `php artisan tinker` → `DB::select('SHOW COLUMNS FROM table')`
- При сложных миграциях использовать `DB::statement('ALTER TABLE ...')` напрямую
- `php artisan migrate --pretend` может показать "Ran" но не выполнить — всегда проверять через `SHOW COLUMNS`

### Таблица `messages_queue`

| Колонка      | Тип          | Default           | Описание                             |
|--------------|--------------|-------------------|--------------------------------------|
| id           | bigint PK    | auto_increment    | первичный ключ                       |
| channel      | varchar(255) | —                 | канал доставки (email, sms, push...) |
| body         | text         | —                 | тело сообщения                       |
| is_processed | tinyint(1)   | 0                 | 0=не обработано, 1=готово            |
| created_at   | timestamp    | CURRENT_TIMESTAMP | дата создания                        |

Модель: `App\Modules\Api\Models\MessageQueue`
- `$timestamps = false`
- `$fillable = ['channel', 'body']`
- `is_processed` — системное поле, не в fillable, не возвращать в API

### Таблица `users`

| Колонка         | Тип          | Default           | Описание                     |
|-----------------|--------------|-------------------|------------------------------|
| id              | bigint PK    | auto_increment    | первичный ключ               |
| login           | varchar(191) | —                 | логин (уникальный)           |
| password        | varchar(255) | —                 | bcrypt-хэш                   |
| is_active       | tinyint(1)   | 1                 | 1=активен, 0=заблокирован    |
| failed_attempts | int          | 0                 | счётчик неудачных попыток    |
| locale          | varchar(5)   | ru                | язык интерфейса (ru/en)      |
| timezone        | varchar(64)  | UTC               | часовой пояс (IANA)          |
| created_at      | timestamp    | CURRENT_TIMESTAMP | дата создания                |
| last_login_at   | timestamp    | NULL              | дата последнего входа        |

Модель: `App\Modules\System\Models\User`
- `$timestamps = false`
- Реализует `FilamentUser`, `HasName`
- `getFilamentName()` → возвращает `$this->login`
- `canAccessPanel()` проверяет `is_active`
- Блокировка после 5 неудачных попыток входа (`MAX_FAILED_ATTEMPTS = 5`)

---

## Filament — особенности этой версии (v4 / PHP 8.3)

### Типы свойств (строгая типизация)

При переопределении свойств нужно точно совпадать с типом родителя:
```php
protected static string|BackedEnum|null $navigationIcon  = '...';
protected static string|\UnitEnum|null  $navigationGroup = '...';
protected static ?int                   $navigationSort  = 1;
protected static ?string                $slug            = '...';
protected string $view = '...';  // НЕ static
```

### Методы

- `form()` принимает `Filament\Schemas\Schema` (не `Filament\Forms\Form`)
- Actions в ресурсах: `Filament\Actions\Action`, `Filament\Actions\DeleteAction`
- Навигационные метки через методы, не свойства: `getNavigationLabel()`, `getModelLabel()`

### Навигация панели

```
🏠 Главная              → /admin (Dashboard)
📄 Документация         → /docs (новая вкладка)

▼ API
   📋 Очередь сообщений → /admin/api/messages

▼ System
   👥 Users             → /admin/system/users
```

---

## Auth / Guard

- Guard: `admin` → provider `system_users` → модель `App\Modules\System\Models\User`
- Определён в `config/auth.php`
- Swagger middleware: `auth:admin`
- Кастомная страница входа: `app/Filament/Pages/Auth/Login.php`
- Кастомная страница профиля: `app/Filament/Pages/Auth/EditProfile.php`

---

## Локализация и часовой пояс

### Locale (ru/en)

- Хранится в `users.locale` (default: `ru`)
- Применяется в `AppServiceProvider::boot()` через `callAfterResolving('auth', ...)`
- Middleware `SetUserLocale` в `authMiddleware` панели
- Файлы переводов: `lang/ru/admin.php`, `lang/en/admin.php`

### Timezone

- Хранится в `users.timezone` (IANA, default: `UTC`)
- Устанавливается через `FilamentTimezone::set(Closure)` в `AppServiceProvider::boot()`
- Все колонки `->dateTime()` в Filament автоматически конвертируют время

---

## Swagger

- UI: `/docs` (защищён `auth:admin`)
- JSON: `/docs/json`
- Аннотации: PHP-атрибуты (swagger-php v6) в `ApiController.php`
- После изменений: `php artisan l5-swagger:generate`
- Конфиг: `config/l5-swagger.php`

---

## Очередь сообщений

Artisan-команда: `queue:processed` (`app/Console/Commands/ProcessQueue.php`)
- Выбирает до 20 записей где `is_processed = 0`
- Одним запросом выставляет `is_processed = 1`
- `withoutOverlapping()` — защита от параллельного запуска
- Лог: `storage/logs/queue-processed.log`
- Расписание: каждые 2 минуты (`everyTwoMinutes()` в `routes/console.php`)

### Системный крон (Linux)

```bash
crontab -e
# Добавить:
* * * * * cd /var/www/laraval.local && php artisan schedule:run >> /dev/null 2>&1
```

---

## Стилизация (public/css/admin.css)

Инжектируется через `PanelsRenderHook::STYLES_AFTER` в `AdminPanelProvider`.

- Тёмный сайдбар: `#292929`
- Текст пунктов: `#d6d6d6`, активный: белый на `rgba(255,255,255,0.12)`
- Заголовки групп: `#888888`, uppercase
- Контент: `padding-inline: 1.5rem`, фон `#f3f4f6`
- Секции без `fi-section-not-contained` получают белый фон с рамкой
- Для виджетов stats (`fi-section-not-contained`): прозрачный фон, без рамки

---

## OpenServer (локальная разработка)

- Домен: `laraval.local`
- Конфиг: `.osp/project.ini`
- PHP engine: `PHP-8.3`
- Public dir: `{base_dir}\public`
- Composer: `C:\Users\admin\AppData\Roaming\Composer\composer.phar`
