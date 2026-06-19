<?php

namespace App\Modules\System\Filament\Pages;

use App\Modules\System\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

/**
 * Глобальные настройки сайта — одностраничная форма (вкладки).
 * Читает/пишет настройки через App\Modules\System\Models\Setting.
 *
 * @property-read Schema $form
 */
class ManageSiteSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon  = 'heroicon-o-cog-6-tooth';
    protected static string|\UnitEnum|null  $navigationGroup = 'System';
    protected static ?int                   $navigationSort  = 6;
    protected static ?string                $slug            = 'system/settings';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    /** Все ключи настроек, которыми управляет форма. */
    public const KEYS = [
        'site_name', 'site_tagline', 'site_logo', 'site_favicon',
        'seo_title_suffix', 'seo_default_description', 'seo_default_keywords', 'seo_index',
        'analytics_head', 'analytics_body',
        'maintenance_mode', 'maintenance_message', 'contact_email',
        'feedback_enabled', 'feedback_limit_per_hour', 'feedback_limit_per_ip',
    ];

    /** Булевы ключи (хранятся как '1'/'0'). */
    private const BOOL_KEYS = ['seo_index', 'maintenance_mode', 'feedback_enabled'];

    /** Файловые ключи (значение — путь на диске public). */
    private const FILE_KEYS = ['site_logo', 'site_favicon'];

    public static function getNavigationLabel(): string
    {
        return __('admin.settings.nav');
    }

    public function getTitle(): string
    {
        return __('admin.settings.title');
    }

    /** Доступ только при наличии права (super_user — в обход). */
    public static function canAccess(): bool
    {
        return (bool) (auth('admin')->user()?->hasPermissionTo('system.settings.view'));
    }

    public function mount(): void
    {
        abort_unless(static::canAccess(), 403);

        $values = Setting::allValues();
        $data = [];

        foreach (self::KEYS as $key) {
            $data[$key] = $values[$key] ?? null;
        }

        foreach (self::BOOL_KEYS as $key) {
            $data[$key] = ($data[$key] ?? '0') === '1';
        }

        // Произвольные ключи — всё, что не входит в фиксированный набор KEYS
        $data['custom'] = [];
        foreach ($values as $key => $value) {
            if (! in_array($key, self::KEYS, true)) {
                $data['custom'][] = ['key' => $key, 'value' => $value];
            }
        }

        $this->form->fill($data);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Tabs::make()
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make(__('admin.settings.tab.general'))
                            ->schema([
                                Forms\Components\TextInput::make('site_name')
                                    ->label(__('admin.settings.field.site_name'))
                                    ->required()
                                    ->maxLength(191),
                                Forms\Components\TextInput::make('site_tagline')
                                    ->label(__('admin.settings.field.site_tagline'))
                                    ->maxLength(255),
                                Forms\Components\FileUpload::make('site_logo')
                                    ->label(__('admin.settings.field.site_logo'))
                                    ->image()
                                    ->disk('public')
                                    ->directory('settings')
                                    ->visibility('public')
                                    ->maxSize(2048),
                                Forms\Components\FileUpload::make('site_favicon')
                                    ->label(__('admin.settings.field.site_favicon'))
                                    ->image()
                                    ->disk('public')
                                    ->directory('settings')
                                    ->visibility('public')
                                    ->maxSize(512),
                            ])
                            ->columns(2),

                        Tab::make(__('admin.settings.tab.seo'))
                            ->schema([
                                Forms\Components\TextInput::make('seo_title_suffix')
                                    ->label(__('admin.settings.field.seo_title_suffix'))
                                    ->helperText(__('admin.settings.field.seo_title_suffix_hint'))
                                    ->maxLength(191),
                                Forms\Components\TextInput::make('seo_default_keywords')
                                    ->label(__('admin.settings.field.seo_default_keywords'))
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('seo_default_description')
                                    ->label(__('admin.settings.field.seo_default_description'))
                                    ->rows(2)
                                    ->columnSpanFull(),
                                Forms\Components\Toggle::make('seo_index')
                                    ->label(__('admin.settings.field.seo_index'))
                                    ->helperText(__('admin.settings.field.seo_index_hint')),
                            ])
                            ->columns(2),

                        Tab::make(__('admin.settings.tab.scripts'))
                            ->schema([
                                Forms\Components\Textarea::make('analytics_head')
                                    ->label(__('admin.settings.field.analytics_head'))
                                    ->helperText(__('admin.settings.field.analytics_head_hint'))
                                    ->rows(4)
                                    ->extraInputAttributes(['style' => 'font-family: monospace;'])
                                    ->columnSpanFull(),
                                Forms\Components\Textarea::make('analytics_body')
                                    ->label(__('admin.settings.field.analytics_body'))
                                    ->helperText(__('admin.settings.field.analytics_body_hint'))
                                    ->rows(4)
                                    ->extraInputAttributes(['style' => 'font-family: monospace;'])
                                    ->columnSpanFull(),
                            ]),

                        Tab::make(__('admin.settings.tab.maintenance'))
                            ->schema([
                                Forms\Components\Toggle::make('maintenance_mode')
                                    ->label(__('admin.settings.field.maintenance_mode'))
                                    ->helperText(__('admin.settings.field.maintenance_mode_hint'))
                                    ->columnSpanFull(),
                                Forms\Components\Textarea::make('maintenance_message')
                                    ->label(__('admin.settings.field.maintenance_message'))
                                    ->rows(2)
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('contact_email')
                                    ->label(__('admin.settings.field.contact_email'))
                                    ->helperText(__('admin.settings.field.contact_email_hint'))
                                    ->email()
                                    ->maxLength(191),
                            ]),

                        Tab::make(__('admin.settings.tab.feedback'))
                            ->schema([
                                Forms\Components\Toggle::make('feedback_enabled')
                                    ->label(__('admin.settings.field.feedback_enabled'))
                                    ->helperText(__('admin.settings.field.feedback_enabled_hint'))
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('feedback_limit_per_hour')
                                    ->label(__('admin.settings.field.feedback_limit_per_hour'))
                                    ->helperText(__('admin.settings.field.feedback_limit_hint'))
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0),
                                Forms\Components\TextInput::make('feedback_limit_per_ip')
                                    ->label(__('admin.settings.field.feedback_limit_per_ip'))
                                    ->helperText(__('admin.settings.field.feedback_limit_hint'))
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0),
                            ])
                            ->columns(2),

                        Tab::make(__('admin.settings.tab.custom'))
                            ->schema([
                                Forms\Components\Placeholder::make('custom_hint')
                                    ->hiddenLabel()
                                    ->content(__('admin.settings.custom.hint'))
                                    ->columnSpanFull(),
                                Forms\Components\Repeater::make('custom')
                                    ->hiddenLabel()
                                    ->schema([
                                        Forms\Components\TextInput::make('key')
                                            ->label(__('admin.settings.field.custom_key'))
                                            ->required()
                                            ->maxLength(191)
                                            ->rule('regex:/^[A-Za-z0-9._-]+$/')
                                            ->placeholder('support_phone'),
                                        Forms\Components\TextInput::make('value')
                                            ->label(__('admin.settings.field.custom_value'))
                                            ->maxLength(2000),
                                    ])
                                    ->columns(2)
                                    ->addActionLabel(__('admin.settings.custom.add'))
                                    ->reorderable(false)
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }

    public function save(): void
    {
        abort_unless(auth('admin')->user()?->hasPermissionTo('system.settings.update'), 403);

        $data = $this->form->getState();

        // Произвольные key→value обрабатываем отдельно (не как поле 'custom')
        $custom = $data['custom'] ?? [];
        unset($data['custom']);

        // Файловые поля могут прийти массивом — нормализуем в строку-путь
        foreach (self::FILE_KEYS as $key) {
            if (isset($data[$key]) && is_array($data[$key])) {
                $data[$key] = (string) (reset($data[$key]) ?: '');
            }
        }

        // Типизированные настройки
        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        // Произвольные настройки: записываем присутствующие, удаляем убранные
        $keep = [];
        foreach ($custom as $row) {
            $key = trim((string) ($row['key'] ?? ''));

            // пустые и совпадающие с зарезервированными ключами пропускаем
            if ($key === '' || in_array($key, self::KEYS, true)) {
                continue;
            }

            Setting::set($key, $row['value'] ?? '');
            $keep[] = $key;
        }

        $existingCustom = array_diff(array_keys(Setting::allValues()), self::KEYS);
        $toDelete = array_diff($existingCustom, $keep);

        if ($toDelete) {
            Setting::query()->whereIn('key', $toDelete)->delete();
            Setting::flushCache();
        }

        Notification::make()
            ->success()
            ->title(__('admin.settings.saved'))
            ->send();
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make($this->getFormActions())->key('form-actions'),
                ]),
        ]);
    }

    /**
     * @return array<Action>
     */
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('admin.settings.save'))
                ->submit('save')
                ->keyBindings(['mod+s']),
        ];
    }
}
