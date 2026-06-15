<?php

namespace App\Modules\Cms\Filament\Resources;

use App\Modules\Cms\Filament\Resources\PageResource\Pages;
use App\Modules\Cms\Models\Page;
use App\Modules\System\Filament\Concerns\AuthorizesWithPermissions;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PageResource extends Resource
{
    use AuthorizesWithPermissions;

    protected static ?string $model = Page::class;

    protected static string $permissionPrefix = 'cms.pages';

    protected static string|BackedEnum|null $navigationIcon  = 'heroicon-o-document-duplicate';
    protected static string|\UnitEnum|null  $navigationGroup = 'CMS';
    protected static ?int                   $navigationSort  = 1;
    protected static ?string                $slug            = 'cms/pages';

    public static function getNavigationLabel(): string
    {
        return __('admin.cms.pages.nav');
    }

    public static function getModelLabel(): string
    {
        return __('admin.cms.pages.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.cms.pages.model_plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            // Структурные поля — общие для всех языков
            Section::make(__('admin.cms.pages.section.main'))
                ->schema([
                    Forms\Components\Select::make('parent_id')
                        ->label(__('admin.cms.pages.field.parent'))
                        ->placeholder(__('admin.cms.pages.field.parent_root'))
                        ->relationship(
                            name: 'parent',
                            titleAttribute: 'title',
                            modifyQueryUsing: function ($query, ?Page $record) {
                                if ($record) {
                                    $query->whereNotIn('id', $record->descendantIds());
                                }
                            },
                        )
                        ->searchable()
                        ->preload(),

                    Forms\Components\TextInput::make('slug')
                        ->label(__('admin.cms.pages.field.slug'))
                        ->required()
                        ->maxLength(191)
                        ->unique(ignoreRecord: true)
                        ->helperText(__('admin.cms.pages.field.slug_hint')),

                    Forms\Components\Select::make('template_id')
                        ->label(__('admin.cms.pages.field.template'))
                        ->relationship(
                            name: 'template',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn ($query) => $query->where('is_system', 0),
                        )
                        ->default(fn () => \App\Modules\Cms\Models\Template::where('is_default', 1)->value('id'))
                        ->searchable()
                        ->preload()
                        ->placeholder(__('admin.cms.pages.field.template_default'))
                        ->helperText(__('admin.cms.pages.field.template_hint')),

                    Grid::make()->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label(__('admin.cms.pages.field.active'))
                            ->default(true)
                            ->inline(false),

                        Forms\Components\Toggle::make('is_home')
                            ->label(__('admin.cms.pages.field.home'))
                            ->helperText(__('admin.cms.pages.field.home_hint'))
                            ->default(false)
                            ->inline(false),

                        Forms\Components\TextInput::make('sort_order')
                            ->label(__('admin.cms.pages.field.sort'))
                            ->numeric()
                            ->default(0),
                    ])->columns(3),
                ])
                ->columns(2),

            // Контент и SEO — по вкладке на язык
            Tabs::make()
                ->columnSpanFull()
                ->tabs([
                    Tab::make('English')->schema(self::localeFields('en'))->columns(2),
                    Tab::make('Русский')->schema(self::localeFields('ru'))->columns(2),
                ]),
        ])->columns(1);
    }

    /**
     * Поля контента/SEO для одной локали (привязаны к i18n.{locale}.*).
     *
     * @return array<int, \Filament\Forms\Components\Field>
     */
    protected static function localeFields(string $loc): array
    {
        $title = Forms\Components\TextInput::make("i18n.{$loc}.title")
            ->label(__('admin.cms.pages.field.title'))
            ->maxLength(191)
            ->columnSpanFull();

        // У локали по умолчанию заголовок обязателен и из него генерируется slug
        if ($loc === Page::DEFAULT_LOCALE) {
            $title
                ->required()
                // debounce, НЕ onBlur (blur при «Отменить» в модалке съедает первый клик)
                ->live(debounce: 500)
                ->afterStateUpdated(function (string $operation, $state, Set $set) {
                    if ($operation === 'create') {
                        $set('slug', Str::slug($state));
                    }
                });
        }

        return [
            $title,

            Forms\Components\RichEditor::make("i18n.{$loc}.content")
                ->label(__('admin.cms.pages.field.content'))
                ->extraInputAttributes(['style' => 'min-height: 20rem;'])
                ->columnSpanFull(),

            Forms\Components\TextInput::make("i18n.{$loc}.meta_title")
                ->label(__('admin.cms.pages.field.meta_title'))
                ->maxLength(191)
                ->helperText(__('admin.cms.pages.field.meta_title_hint')),

            Forms\Components\TextInput::make("i18n.{$loc}.meta_keywords")
                ->label(__('admin.cms.pages.field.meta_keywords'))
                ->maxLength(255),

            Forms\Components\Textarea::make("i18n.{$loc}.meta_description")
                ->label(__('admin.cms.pages.field.meta_description'))
                ->maxLength(500)
                ->rows(3)
                ->columnSpanFull(),
        ];
    }

    /**
     * Синхронизация legacy-колонок (title/content/meta) с локалью по умолчанию —
     * чтобы дерево в админке и фолбэки работали как раньше.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function syncDefaultLocale(array $data): array
    {
        $def  = Page::DEFAULT_LOCALE;
        $i18n = $data['i18n'] ?? [];
        $base = $i18n[$def] ?? ($i18n['ru'] ?? []);

        $data['title']            = $base['title'] ?? ($data['title'] ?? 'Untitled');
        $data['content']          = $base['content'] ?? null;
        $data['meta_title']       = $base['meta_title'] ?? null;
        $data['meta_description'] = $base['meta_description'] ?? null;
        $data['meta_keywords']    = $base['meta_keywords'] ?? null;

        return $data;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->orderBy('parent_id')->orderBy('sort_order')->orderBy('id'))
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('admin.cms.pages.col.title'))
                    ->formatStateUsing(function (string $state, Page $record) {
                        $prefix = $record->depth > 0
                            ? str_repeat('— ', $record->depth)
                            : '';

                        return $prefix . $state;
                    })
                    ->searchable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label(__('admin.cms.pages.col.slug'))
                    ->badge()
                    ->color('gray')
                    ->copyable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('template.name')
                    ->label(__('admin.cms.pages.col.template'))
                    ->badge()
                    ->color('gray')
                    ->placeholder('—'),

                Tables\Columns\IconColumn::make('is_home')
                    ->label(__('admin.cms.pages.col.home'))
                    ->boolean()
                    ->trueIcon('heroicon-o-home')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('warning')
                    ->falseColor('gray'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('admin.cms.pages.col.active'))
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('admin.cms.pages.col.sort'))
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('admin.cms.pages.col.created_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->recordUrl(fn (Page $record) => static::getUrl('edit', ['record' => $record]))
            ->actions([
                Action::make('open')
                    ->label(__('admin.cms.pages.action.open'))
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('gray')
                    ->url(fn (Page $record) => $record->url, shouldOpenInNewTab: true)
                    ->visible(fn (Page $record) => $record->is_active),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit'   => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
