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
    │   ├── Role.php                  # Роль (таблица roles), belongsToMany users
    │   └── Translation.php           # Перевод UI (таблица translations, i18n в БД)
    ├── Support/
    │   └── DatabaseTranslationLoader.php  # загрузчик переводов из БД (extends FileLoader)
    └── Filament/
        └── Resources/
            ├── UserResource.php      # CRUD пользователей
            │   └── UserResource/Pages/{List,Create,Edit}User.php
            ├── RoleResource.php      # CRUD ролей (multi-select пользователей)
            │   └── RoleResource/Pages/{List,Create,Edit}Role.php
            └── TranslationResource.php  # CRUD переводов (инлайн-правка value)
                └── TranslationResource/Pages/{List,Create,Edit}Translation.php
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
- Стили лендинга — в `resources/scss/landing.scss` → `public/css/landing.css` (сборка `npm run css`,
  см. раздел «Стилизация (SCSS → CSS)»); шаблон подключает её через `<link ... ?v={{ filemtime(...) }}>`
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

### SEO-выхлоп

**`App\Modules\Cms\Http\Controllers\SeoController`**, роуты в `routes/web.php` (БЕЗ языкового префикса):
- `GET /sitemap.xml` (`seo.sitemap`) — каждый URL по всем активным языкам с `xhtml:link hreflang`
  + `x-default`, `lastmod`. Кэшируется через FrontCache (ключ с хостом), сбрасывается на правках.
  **Ссылки собираются из модулей через реестр `App\Modules\Cms\Support\Sitemap\Sitemap`** (без
  прямых зависимостей контроллера на модули):
  - `SitemapSource` (интерфейс, метод `entries(): iterable<SitemapUrl>`), `SitemapUrl`
    (`Closure(locale):string $loc` + `$lastmod`).
  - `Sitemap::sources()` — авто-обнаружение по соглашению: `glob('app/Modules/*/Sitemap/*.php')`,
    берёт классы, реализующие `SitemapSource` (как `discoverResources` в Filament). Мемоизация на запрос.
  - Поставщики: `App\Modules\Cms\Sitemap\PageSitemap` (страницы), `App\Modules\Blog\Sitemap\BlogSitemap`
    (лента + статьи). **Удаление модуля убирает его папку `Sitemap/` → его URL просто исчезают из карты,
    ядро не падает** (проверено: без `BlogSitemap` карта отдаёт 200 без блога). Сбойный поставщик
    оборачивается try/catch и не роняет всю карту.
- `GET /robots.txt` (`seo.robots`) — динамический: `maintenance_mode` ИЛИ `seo_index=false` → `Disallow: /`,
  иначе `Disallow: /admin|/api|/docs` + `Sitemap:`. ⚠️ Статический `public/robots.txt` УДАЛЁН
  (иначе Apache отдавал бы его до Laravel).
- **canonical / Open Graph / Twitter / `meta robots`** — в системном шаблоне `head` (пропатчен
  миграцией `..._seo_meta_and_redirects`, идемпотентно). `og:image` берётся из настройки `seo_og_image`
  (SEO-вкладка настроек, FileUpload). `noindex,nofollow` рендерится при `seo_index=false`.

### Редиректы 301/302 (`redirects`)

- Таблица `redirects` (`from_path`/`to_path` — пути БЕЗ языка и ведущего слэша, `status`, `is_active`,
  `hits`). Модель `App\Modules\Cms\Models\Redirect`: `activeMap()` (мапа правил, устойчива к отсутствию
  таблицы), `capture($from,$to)` (сшивает цепочки A→B→C, не плодит петли).
- Middleware `App\Http\Middleware\HandleRedirects` — ПЕРВЫМ в языковой группе (до `CheckMaintenance`):
  разбирает `{locale}/{rest}`, ищет `rest` в `activeMap()`, отдаёт `redirect()->to(/{locale}/{to}, status)`,
  инкрементит `hits`. Slug страниц общий для всех локалей → одно правило работает во всех языках.
- **Авто-захват:** `Page` на смене `slug` (`updating`/`updated`) фиксирует старые пути поддерева
  (`subtreePaths()` — страница + потомки) и заводит 301 old→new для каждого изменившегося пути.
  Решает «сменили slug → старый URL стал 404».
- `RedirectResource` (CMS, sort 6, модалки, permissionPrefix `cms.redirects`, в дереве прав).

### Кэш публичного фронта (`App\Modules\Cms\Support\FrontCache`)

Конфиг `config/cms.php`: `front_cache` (env `CMS_FRONT_CACHE`, default true),
`front_cache_ttl` (default 86400). Store по умолчанию — `database` (таблица `cache`).

Подход — **«поколение» (version)**: все ключи содержат `cms.front.v{N}.…`; сброс всего кэша =
bump `N` (`Cache::forever('cms.front.ver', N+1)`), старые ключи становятся недостижимы и гаснут по TTL.
Теги не используются (их не поддерживает database-store). Сброс делает `FrontCache::flush()`.

**Два уровня:**
1. **Поиск тел шаблонов / значений блоков по slug** (`TemplateRenderer::body()`/`block()`) — снимает
   повторные запросы в БД на каждый `@partial`/`@block`. Рендер остаётся живым → CSRF и пр.
   per-request данные НЕ замораживаются. Request-мемоизация (`$bodyCache`/`$blockCache`) поверх FrontCache.
2. **Полностраничный HTML** (`PageController::render`) — **только для «статичных» страниц**.
   `TemplateRenderer::chainIsDynamic($slug)` рекурсивно сканирует тело + все `@partial` на маркеры
   `@csrf`/`csrf_token`/`old(`/`$errors`/`session(`; если найдены — страница НЕ кэшируется целиком
   (главная с формой обратной связи). Ключ `page.{id}.{locale}.{md5(host+scheme)}`,
   результат детектора — `dyn.{slug}`. ⚠️ **host+scheme в ключе обязателен:** HTML содержит
   абсолютные URL (`url()`/`asset()`), привязанные к хосту запроса — без этого рендер под другим
   хостом (CLI/tinker = `localhost`, второй домен, http/https) отравил бы кэш чужими ссылками.

**Инвалидация:** `FrontCache::listen()` в `AppServiceProvider::boot` вешает `saved`/`deleted` →
`flush()` на Page, Template, Block, MenuItem, FooterColumn, FooterLink, Setting, Language.
Любая правка контента в админке сбрасывает весь фронт-кэш автоматически.

**Управление в админке:** «Настройки сайта» → вкладка **«Кэш»** (`ManageSiteSettings`):
тумблер `front_cache` (настройка-источник правды для `FrontCache::enabled()`, дефолт —
`config('cms.front_cache')`), показ текущего поколения и кнопка **«Сбросить кэш»**
(`FrontCache::flush()`, под правом `system.settings.update`). Сид настройки + переводы вкладки —
миграция `..._front_cache_setting_and_translations.php`.

## Модуль «Галерея» (app/Modules/Gallery)

Фотогалерея — порт модуля Gallery с alexanderwizard.com, адаптированный под
Laravel+Filament. Альбомы + фотографии с EXIF, серверная генерация превью на GD.

**Таблицы** (миграция `..._create_gallery_tables.php`, типы под MySQL 5.6 — `i18n` = `LONGTEXT`, не JSON):
- `gallery_albums`: id, title, slug(unique), description, i18n(LONGTEXT/array),
  photos_count, is_active, sort_order, created_at, updated_at. Модель
  `App\Modules\Gallery\Models\Album` (`HasI18n`, `LogsActivity`, `I18N_FIELDS=['title','description']`).
  `activePhotos()`, `refreshCounter()` (saveQuietly — пересчёт счётчика + updated_at),
  scope `activeFeed()`, `getUrlAttribute` → `/{locale}/gallery/{slug}`.
- `gallery_photos`: id, album_id(FK CASCADE), path, title, tags, i18n, width, height,
  size, camera, lens, shutter_speed, focal_length, iso, taken_at, year, is_active,
  **views** (счётчик просмотров), sort_order, created_at. Модель `App\Modules\Gallery\Models\Photo`
  (`HasI18n`, `LogsActivity`). `path` = базовый путь варианта на диске `gallery` БЕЗ расширения
  (`{album_id}/{id}`); файлы: `{path}.jpg` (1680², лайтбокс), `_med.jpg` (≤1080, НЕ обрезан —
  masonry-сетка), `_tmb.jpg` (квадрат 600², обложки), `_tmb2.jpg` (квадрат 96², лента).
  Аксессоры `image_url`/`med_url`/`thumb_url`/`micro_url` (Storage::url), `url` (страница фото),
  `aspectRatio()` (для masonry без сдвига), `viewsLabel()` (1.2k/3.4M), `isPortrait()`, `tagList()`.
  События: `created`/`deleted` → `album->refreshCounter()`, `deleted` → `deleteFiles()`.

**Обработка изображений (`App\Modules\Gallery\Support`):**
- `ImageProcessor` (GD) — `fitJpeg()` (вписать в бокс, без обрезки), `squareThumbnail()`
  (квадрат по центру), `dimensions()`. Тихо возвращает false без GD/битого файла.
- `Exif` — `read()` нормализует EXIF (камера/объектив/выдержка/диафрагма/ISO/дата); устойчив
  к отсутствию расширения `exif`.
- `PhotoUploader::ingest(Album, $sourcePath)` — создаёт запись Photo (сначала, чтобы id = имя
  файла), генерирует 4 варианта (jpg/_med/_tmb/_tmb2) в `{album_id}/{id}`, дописывает EXIF.

⚠️ **Хранилище файлов — диск `gallery` = `public/uploads/gallery`** (config/filesystems.php),
URL относительный `/uploads/gallery/...`. НЕ `storage/app/public`: на Windows/OpenServer symlink
`public/storage` — это MSYS-symlink с Unix-целью, который Apache не разворачивает (битые превью).
Файлы лежат прямо под `public/` (как в оригинале alexanderwizard.com `public/uploads/photos`),
веб-сервер отдаёт их напрямую. Относительный URL ещё и хост-независим (хорошо для FrontCache).

**Админка (Filament, группа «Gallery»):**
- `AlbumResource` (sort 1, permissionPrefix `gallery.albums`) — модальный CRUD (только `index`
  в getPages), вкладки по языкам (title/description i18n), slug-автоген; экшены строки:
  «фото» (→ PhotoResource с фильтром по альбому), «открыть на сайте», edit/delete.
- `PhotoResource` (sort 2, permissionPrefix `gallery.photos`) — модальный edit (album/caption
  i18n/tags/is_active), ImageColumn-превью, фильтр по альбому. **Массовая загрузка**:
  `PhotoResource::uploadAction()` в шапке списка (`ListPhotos`) — Select альбома +
  `FileUpload->multiple()` (диск `gallery`, `_tmp`); в `->action()` каждый файл →
  `PhotoUploader::ingest()`, временный исходник удаляется.

**Публичный фронт (редизайн в духе 500px/Unsplash):** `GalleryController` —
- `index` — **discover-стена**: masonry обложек альбомов (обложка = свежее фото `_med`).
- `album` — **masonry-сетка** (CSS-колонки, кадры не режутся) + **бесконечная подгрузка**:
  при `?partial=1&page=N` отдаёт ТОЛЬКО плитки (partial `gallery._tiles`, без лейаута),
  фронт догружает через `IntersectionObserver`. PER_PAGE=30. Клик по плитке → **клиентский
  лайтбокс** (без перезагрузки): крупный кадр + EXIF-панель + prev/next + клавиатура (`←`/`→`/`Esc`)
  + счётчик; данные берутся из `data-*` плитки, при открытии — ping `gallery.photo.view`.
- `photo` — **иммерсивная shareable-страница** (Open Graph/Twitter), инкрементит `views`;
  крупный кадр + EXIF-сайдбар + лента соседних кадров. Используется для deep-link/SEO.
- `viewPing` (`GET /gallery/{slug}/{id}/v` → 204) — лёгкий инкремент просмотров из лайтбокса
  (GET без CSRF; `increment` без модельных событий → НЕ сбрасывает FrontCache).

Роуты в языковой группе ДО catch-all `cms.page`: `/{locale}/gallery`, `/gallery/{slug}`,
`/gallery/{slug}/{id}/v` (ping, ДО фото), `/gallery/{slug}/{id}`. Вьюхи
`resources/views/gallery/{albums,album,photo,_tiles}.blade.php`; логика лайтбокса и infinite
scroll — `public/js/gallery.js` (vanilla, без зависимостей; подключается с `?v=filemtime`).
Стили — секция «Галерея — редизайн» в `landing.scss` (классы `gl-*`). Галерея НЕ кэшируется
целиком (контроллер не использует FrontCache page-level), просмотры всегда свежие.

**Интеграции:** `GallerySitemap` (Sitemap-реестр), Album/Photo в `FrontCache::BUSTERS`,
ветка `gallery` в `Permissions::tree()`, `discoverResources` для Gallery в `AdminPanelProvider`,
строки UI — миграция `..._seed_gallery_translations.php` (ключи `gallery.*`).

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
  `RoleResource` (`system.roles`), `FirewallResource` (`system.firewall`),
  `ActionLogResource` (`system.actions`), `TranslationResource` (`system.translations`).

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
   🈯 Переводы           → /admin/system/translations (sort 5, i18n в БД)
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

### Переводы интерфейса — в БД (таблица `translations`), НЕ в lang-файлах

**Источник правды для строк UI — таблица `translations`, а не `lang/*.php`** (их больше нет).

- Таблица `translations`: `group`(default `admin`), `key`(плоский, с точками — `users.nav`),
  `locale`(ru/en), `value`, `created_at`. UNIQUE(`group,key,locale`). Модель
  `App\Modules\System\Models\Translation` (`$timestamps=false`, LogsActivity).
- Загрузчик `App\Modules\System\Support\DatabaseTranslationLoader` (extends `FileLoader`):
  берёт файловые строки (для vendor/namespaced групп), затем подмешивает строки из БД сверху
  (БД приоритетна), мемоизация на запрос, устойчив к отсутствию таблицы (тихий фолбэк).
- Регистрация: `AppServiceProvider::register()` через **`$this->app->extend('translation.loader', …)`**
  (НЕ `singleton` — `TranslationServiceProvider` отложенный и лениво биндит свой `FileLoader`,
  `extend` применяется поверх позднего биндинга).
- Использование в коде не меняется: `__('admin.users.nav')` и т.п.
- Начальный сид — `database/data/admin_translations.php` (массив строк), грузится миграцией
  `..._create_translations_table.php` (для `migrate`/`migrate:fresh`).
- Редактирование — раздел **Переводы** (`/admin/system/translations`, `TranslationResource`,
  permissionPrefix `system.translations`): таблица с инлайн-правкой `value` (`TextInputColumn`),
  фильтры по локали и группе. Правка сразу влияет на `__()` (кэша между запросами нет).
- Добавить язык = добавить строки с новым `locale` (и расширить `TranslationResource::LOCALES`).

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

## Стилизация (SCSS → CSS)

**Источник правды — SCSS, а не CSS.** Все стили компилируются из `resources/scss/` в `public/css/`.
**Не редактировать `public/css/*.css` руками** — менять `.scss` и пересобирать.

- Исходники: `resources/scss/admin.scss` (админ-панель), `resources/scss/landing.scss` (лендинг)
- Сборка: `npm run css` (одноразово) / `npm run css:watch` (вотчер) — компилирует оба файла
  через `sass … --style=compressed --no-source-map`
- `sass` запинен в `devDependencies` на `1.69.6` (новые версии падают `ERR_REQUIRE_ESM`
  под Node 20 при `"type":"module"`); установка — `npm install`
- `admin.scss` использует SCSS-переменные (палитры: сайдбар / светлая тема / тёмная) и вложенность

`admin.css` инжектится через `PanelsRenderHook::STYLES_AFTER` в `AdminPanelProvider`
(версионируется `?v=filemtime`).

### Тёмная тема (`html.dark`)

Базовые правила `admin.scss` форсят светлый вид через `!important`. Filament по умолчанию
следует системной теме (`localStorage theme="system"`); в dark-режиме Chrome/ОС ставит
`<html class="fi dark">` с белым текстом → без тёмной ветки было бы «белое на белом».
В конце `admin.scss` — блок `html.dark { … }` с тёмными эквивалентами (фон контента/топбара,
карточки виджетов/таблиц/секций, цвета текста/активных табов). Префикс `html.dark` поднимает
специфичность над базовыми правилами. **Любое новое светлое правило дублировать в `html.dark`.**
Сайдбар намеренно тёмный в обеих темах — не трогаем.

### AJAX-лоадер модалок

Центрированный спиннер-оверлей при загрузке модалки (открытие любого экшена с формой —
create/edit/delete). Реализация в `AdminPanelProvider`:
- Оверлей `<div class="app-modal-loader">` — рендер-хук `PanelsRenderHook::BODY_END`
  (стиль `.app-modal-loader`/`__spinner` в `admin.scss`, `position:fixed`, `z-index:99999`,
  затемнение+blur, вращающийся бордюр; **`display` без `!important`** — управляется из JS).
- Скрипт (`PanelsRenderHook::SCRIPTS_AFTER`): `Livewire.hook('request', …)` — `opts.payload`
  приходит **JSON-строкой**, парсим, и если среди `components[].calls[].method` есть
  `mountAction`/`mountTableAction`/`mountFormComponentAction` — показываем оверлей, на
  `succeed`/`fail` прячем. На обычных запросах (сортировка/поиск/пагинация) не показывается.
- ⚠️ `wire:loading` тут НЕ годится: `BODY_END`/`CONTENT_END` рендерятся ВНЕ Livewire-компонента
  страницы (`closest('[wire:id]')` = null), поэтому директива не сработала бы — отсюда JS-хук.

### Светлая тема

- Тёмный сайдбар: `#292929`
- Текст пунктов: `#d6d6d6`, активный: белый на `rgba(255,255,255,0.12)`
- Заголовки групп: `#888888`, uppercase
- Контент: `padding-inline: 1.5rem`, фон `#f3f4f6`
- Секции без `fi-section-not-contained` получают белый фон с рамкой
- Для виджетов stats (`fi-section-not-contained`): прозрачный фон, без рамки
- Ширина сайдбара: переопределена `--sidebar-width: 14.3rem` (контент сдвигается сам)
- Таблицы: тело строк компактное — `padding-block` задан на контенте ячейки
  (`.fi-ta-row .fi-ta-text/.fi-ta-color/.fi-ta-icon`, `0.2rem`), заголовки не трогаем.
  ⚠️ Высоту строки в одиночку держит **ячейка действий** (`py-4`=16px), её правило не ловит
  `el.matches()` — для компактности отдельно `.fi-ta-row .fi-ta-cell:has(.fi-ta-actions){padding-block:0.2rem}`
  (синхронно с контентом ячеек). У `.fi-ta-ctn` НЕ ставить `overflow:hidden` (обрезает дропдаун фильтров)

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
