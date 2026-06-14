# CLAUDE.md — x5 skeleton

Инструкции для Claude Code при работе с этим репозиторием.

---

## Стек

- **Laravel 13.14.0**, PHP 8.3.6
- **MySQL 5.6** — IP `127.127.126.24:3306`, БД `x5_cms`, пользователь `root`, пароль пустой
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
DB_DATABASE=x5_cms
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
├── Cms/
│   ├── Models/
│   │   ├── Page.php                  # Страница сайта (таблица pages, дерево через parent_id)
│   │   └── Template.php              # Шаблон вывода (таблица templates, Blade-разметка)
│   ├── Http/Controllers/PageController.php  # Публичный фронт
│   └── Filament/
│       └── Resources/
│           ├── PageResource.php      # CRUD дерева страниц
│           │   └── PageResource/Pages/{List,Create,Edit}Page.php
│           └── TemplateResource.php  # CRUD шаблонов
│               └── TemplateResource/Pages/{List,Create,Edit}Template.php
└── System/
    ├── Models/
    │   ├── User.php                  # Системный пользователь (таблица users)
    │   └── Role.php                  # Роль (таблица roles), belongsToMany users
    └── Filament/
        └── Resources/
            ├── UserResource.php      # CRUD пользователей
            │   └── UserResource/Pages/{List,Create,Edit}User.php
            └── RoleResource.php      # CRUD ролей (multi-select пользователей)
                └── RoleResource/Pages/{List,Create,Edit}Role.php
```

PSR-4 namespace: `App\Modules\*` — автоматически покрывается `"App\\": "app/"` в `composer.json`.

### Регистрация модулей в Filament

`app/Providers/Filament/AdminPanelProvider.php`:
```php
->discoverResources(in: app_path('Modules/Api/Filament/Resources'), for: 'App\Modules\Api\Filament\Resources')
->discoverResources(in: app_path('Modules/Cms/Filament/Resources'), for: 'App\Modules\Cms\Filament\Resources')
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

### Таблица `pages` (модуль CMS)

| Колонка    | Тип          | Default           | Описание                          |
|------------|--------------|-------------------|-----------------------------------|
| id         | bigint PK    | auto_increment    | первичный ключ                    |
| parent_id  | bigint FK    | NULL              | → pages.id (ON DELETE CASCADE)    |
| title      | varchar(191) | —                 | заголовок                         |
| slug       | varchar(191) | —                 | URL (unique)                      |
| content    | longtext     | NULL              | содержимое (RichEditor)           |
| is_home    | tinyint(1)   | 0                 | 1 = главная лендинга (только одна)|
| is_active  | tinyint(1)   | 1                 | опубликована                      |
| sort_order | int          | 0                 | порядок среди соседей             |
| created_at | timestamp    | CURRENT_TIMESTAMP | дата создания                     |

Модель: `App\Modules\Cms\Models\Page`
- `$timestamps = false`; дерево через `parent()` / `children()` (self-reference)
- `getDepthAttribute()` — глубина вложенности; `descendantIds()` — id поддерева (для исключения из выбора родителя, защита от циклов)
- `booted()::saving` — при `is_home=1` снимает флаг со всех остальных (главная одна)
- Трейт `LogsActivity` (журнал действий)
- Миграция засевает главную страницу (`title='Главная', slug='home', is_home=1`)
- `PageResource`: таблица в tree-порядке (`parent_id, sort_order, id`), заголовок с отступом по глубине;
  главную нельзя удалить (`DeleteAction->visible(!is_home)`); action «Открыть на сайте». permissionPrefix `cms.pages`.

**Шаблоны (таблица `templates`):**
- Колонки: id, name, slug(unique), is_system, body(longtext — Blade-разметка), created_at
- Модель `App\Modules\Cms\Models\Template`: `pages()` hasMany, трейт `LogsActivity`
- `pages.template_id` → FK на `templates` (ON DELETE SET NULL); `Page::template()` belongsTo
- Шаблон `home` (засеян миграцией) = лендинг главной; главной странице назначен `template_id`
- Стили лендинга — в `resources/scss/landing.scss` → компиляция в `public/css/landing.css`
  (`npx sass resources/scss/landing.scss public/css/landing.css --style=compressed --no-source-map`);
  шаблон подключает её через `<link ... ?v={{ filemtime(...) }}>`
- В `body` доступны переменные при рендере: **`$title`, `$content` (тело страницы), `$children`, `$page`, `$appName`**.
  Вывод тела страницы внутри шаблона — `{!! $content !!}`
- **Системные шаблоны** (`is_system=1`): `header`, `menu`, `footer` — частичные, защищены от удаления.
  Лендинг `home` инклюдит их.
- **Блоки (таблица `blocks`)** — переиспользуемые текстовые фрагменты (телефон, e-mail, адрес).
  Модель `App\Modules\Cms\Models\Block` (name, slug unique, value, LogsActivity).
  Директива **`@block('slug')`** (зарег. в `AppServiceProvider::boot`) → `TemplateRenderer::block($slug)`
  (кэш на запрос). `BlockResource` (CMS, sort 3), permissionPrefix `cms.blocks`, в дереве прав.
- **Директива `@partial('slug')`** — инклюд шаблона CMS из БД (зарегистрирована в `AppServiceProvider::boot`,
  компилируется в `TemplateRenderer::partial($slug, get_defined_vars())`).
  `App\Modules\Cms\Support\TemplateRenderer::render/partial` находит шаблон по slug и рендерит его
  `Blade::render` с переменными родителя (служебные `__*`, `app`, `errors` вычищаются).
  Поддерживает вложенность (header → menu).
- `TemplateResource` (CMS, sort 2): name/slug/is_system + Textarea с кодом (моноширинный);
  системные нельзя удалить. permissionPrefix `cms.templates`

**Публичный фронт CMS:**
- Контроллер `App\Modules\Cms\Http\Controllers\PageController` (`home()`, `show($path)`)
- Роуты `routes/web.php`: `/` → `cms.home` (главная), `/{path}` → `cms.page` (catch-all,
  регуляркой `^(?!admin|api|docs|login)...` исключает служебные префиксы, регистрируется ПОСЛЕДНИМ)
- **Рендер:** если у страницы есть `template` с непустым `body` → `Blade::render($template->body, $data)`;
  иначе — встроенный `resources/views/cms/page.blade.php` (fallback)
- URL: главная на `/` (её slug «home» прозрачен и в путь не попадает); дети главной → `/about`,
  `/about/team`; корни-сиблинги → `/services`. Неактивные и `/home` → 404.
- `Page`: `findByPath($path)` (резолв по иерархии, первый сегмент = корень ИЛИ ребёнок главной),
  `getPathAttribute`, `getUrlAttribute`, `ancestorsTrail()`, `home()`
- ⚠️ **Безопасность:** `Blade::render()` компилирует и исполняет PHP — тело шаблона = доверенный код
  только админов с правом `cms.templates`. Контент страницы выводится как HTML (`{!! $content !!}`)
  — право `cms.pages`. Менее доверенным ролям эти права не выдавать (нужна изоляция/санитизация).

### Таблица `users`

| Колонка         | Тип          | Default           | Описание                     |
|-----------------|--------------|-------------------|------------------------------|
| id              | bigint PK    | auto_increment    | первичный ключ               |
| login           | varchar(191) | —                 | логин (уникальный)           |
| password        | varchar(255) | —                 | bcrypt-хэш                   |
| is_active       | tinyint(1)   | 1                 | 1=активен, 0=заблокирован    |
| super_user      | tinyint(1)   | 0                 | 1=полный доступ ко всему     |
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
- `roles()` — belongsToMany через `role_user`

### Таблица `roles` и сводная `role_user`

`roles`:

| Колонка     | Тип          | Default           | Описание           |
|-------------|--------------|-------------------|--------------------|
| id          | bigint PK    | auto_increment    | первичный ключ     |
| name        | varchar(191) | —                 | название (unique)  |
| description | varchar(255) | NULL              | описание           |
| created_at  | timestamp    | CURRENT_TIMESTAMP | дата создания      |

`role_user` (pivot, many-to-many):

| Колонка | Тип       | Описание                                    |
|---------|-----------|---------------------------------------------|
| id      | bigint PK | первичный ключ                              |
| role_id | bigint FK | → roles.id (ON DELETE CASCADE)              |
| user_id | bigint FK | → users.id (ON DELETE CASCADE)              |
| —       | UNIQUE    | пара (role_id, user_id) уникальна           |

Модель: `App\Modules\System\Models\Role`
- `$timestamps = false`, `$fillable = ['name', 'description']`
- `users()` — belongsToMany через `role_user`

Ресурс `RoleResource`: множественный выбор пользователей при редактировании роли —
```php
Forms\Components\Select::make('users')
    ->relationship('users', 'login')->multiple()->preload()->searchable()
```
Filament синхронизирует pivot `role_user` автоматически (на create и edit).

### Права доступа (permissions)

- Колонка `roles.permissions` (TEXT, каст `array`) хранит плоский список ключей вида
  `"{module}.{resource}.{ability}"`, напр. `api.messages.view`, `system.users.update`.
- Реестр дерева: `App\Modules\System\Support\Permissions`
  - `tree()` — модуль → ресурсы; `ABILITIES = [view, create, update, delete]`
  - `all()` — все валидные ключи; `abilityOptions()`, `groupKey()`
  - **добавить ресурс в дерево прав = одна строка в `tree()`**
- Форма роли: секция на модуль (collapsible) + `CheckboxList` на ресурс.
  Конвертация плоский ↔ сгруппированный — трейт
  `RoleResource\Concerns\HandlesPermissions` (mutate-хуки Create/Edit, с валидацией по `Permissions::all()`).

### Энфорсмент (применение прав)

- `User`:
  - `roles()` belongsToMany; `allPermissions()` — объединение прав всех ролей (кэш на запрос)
  - `hasPermissionTo($key)` — проверка (true сразу, если `isSuperAdmin()`)
  - `isSuperAdmin()` — **флаг `users.super_user`**: полный доступ ко всему в обход ролей/прав.
    Обычный пользователь без ролей доступа не имеет. Существующие на момент ввода прав
    пользователи помечены `super_user=1` миграцией (страховка от блокировки).
- Трейт `App\Modules\System\Filament\Concerns\AuthorizesWithPermissions`:
  ресурс объявляет `protected static string $permissionPrefix = '...'`, трейт переопределяет
  `canViewAny/canView/canCreate/canEdit/canDelete/canDeleteAny`.
  - `canViewAny=false` скрывает ресурс из меню (пустые группы Filament прячет сам)
- Подключён в `MessageQueueResource` (`api.messages`), `UserResource` (`system.users`),
  `RoleResource` (`system.roles`), `FirewallResource` (`system.firewall`).

### Firewall (IP-allowlist)

- Таблица `firewall_rules` (ip_address, description, is_active, created_at)
- Модель `App\Modules\System\Models\FirewallRule`:
  - `isAllowed($ip)` — **пустой список активных правил = доступ открыт** (защита от блокировки),
    иначе IP должен совпасть с правилом
  - `ipMatches($ip, $rule)` — точный IP или CIDR (IPv4/IPv6); `isValidIpOrCidr()` — валидация
- Middleware `App\Http\Middleware\CheckFirewall` — в `->middleware([...])` панели (первым,
  до сессии/авторизации; блокирует даже страницу логина). При отказе `abort(403)`.
- Ресурс `FirewallResource` (System, sort 3). Поле IP валидируется через `isValidIpOrCidr`.

### Журнал действий (Actions / audit log)

- Таблица `action_logs` (user_id, user_login, event, subject_type, subject_label, subject_id,
  properties JSON, ip_address, created_at)
- Модель `App\Modules\System\Models\ActionLog`:
  - `log($event, ?$subject, ?$actor, ?$properties)` — пишет запись; **только если есть
    авторизованный админ** (actor или `auth('admin')->user()`), иначе пропускает
    (действия API/крона не логируются)
  - `labelFor($model)` — человекочитаемое имя раздела по классу модели
- Трейт `App\Modules\System\Support\LogsActivity` — `use` в модели вешает события
  `created/updated/deleted` → `ActionLog::log(...)` (для updated пишет изменённые поля).
  Подключён: `User`, `Role`, `FirewallRule`.
- Событие `login` пишется явно в `Login::authenticate()`.
- Ресурс `ActionLogResource` (System, sort 4) — **read-only** (`canCreate/Edit/Delete=false`),
  фильтры по событию и пользователю. permissionPrefix `system.actions`.

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

▼ CMS
   📄 Страницы          → /admin/cms/pages     (sort 1)
   🧩 Шаблоны           → /admin/cms/templates (sort 2)
   🔤 Блоки             → /admin/cms/blocks    (sort 3)

▼ System
   👥 Users             → /admin/system/users    (sort 1)
   🛡 Roles             → /admin/system/roles    (sort 2)
   🧱 Firewall          → /admin/system/firewall (sort 3)
   📋 Actions           → /admin/system/actions  (sort 4, журнал, read-only)
```

Корни модулей без index-маршрута (`/admin/api`, `/admin/system`) рендерят страницу 404
внутри лейаута админки. Базовый абстрактный класс `App\Filament\Pages\NotFoundPage`,
конкретные наследники задают только `$slug` (`ApiNotFoundPage`, `SystemNotFoundPage`).
Шаблон: `resources/views/filament/pages/not-found.blade.php`.

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
- Ширина сайдбара: переопределена `--sidebar-width: 14.3rem` (контент сдвигается сам)
- Таблицы: тело строк компактное — `padding-block` задан на контенте ячейки
  (`.fi-ta-row .fi-ta-text/.fi-ta-color/.fi-ta-icon`, ~0.35rem), заголовки не трогаем;
  у `.fi-ta-ctn` НЕ ставить `overflow:hidden` (обрезает дропдаун фильтров)

### Табы — глобальный стиль «подчёркивание активного»

Применяется ко всем табам админки (правила в `admin.css`, нацелены на `.fi-tabs`/`.fi-tabs-item`):
- `.fi-tabs` — прозрачный фон, нижняя линия `1px #e5e7eb`, без скруглений/паддингов,
  `overflow: visible` (иначе появляется скроллбар-артефакт ↕ от `overflow-x:auto`),
  `padding-left: 25px`, выравнивание влево
- `.fi-tabs-item` — без «пилюль», серый `#6b7280`, `border-bottom: 2px transparent`,
  `margin-bottom: -1px`, паддинг `1.15rem 1.5rem 0.5rem` (текст прижат к линии)
- `.fi-tabs-item.fi-active` — `border-bottom: 2px #111827`, текст чёрный жирный
- `.fi-tabs .fi-dropdown { display:none }` — на всякий случай прячем оверфлоу-дропдаун
- В коде у `Tabs` иконки не задаём; `contained` оставляем по умолчанию (true) —
  `contained(false)` ломает раскладку (правый дропдаун со стрелками)

---

## OpenServer (локальная разработка)

- Домен: `laraval.local`
- Конфиг: `.osp/project.ini`
- PHP engine: `PHP-8.3`
- Public dir: `{base_dir}\public`
- Composer: `C:\Users\admin\AppData\Roaming\Composer\composer.phar`
