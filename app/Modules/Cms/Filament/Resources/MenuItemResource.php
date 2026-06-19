<?php

namespace App\Modules\Cms\Filament\Resources;

use App\Modules\Cms\Filament\Resources\MenuItemResource\Pages;
use App\Modules\Cms\Models\MenuItem;
use App\Modules\Cms\Models\Page;
use App\Modules\System\Filament\Concerns\AuthorizesWithPermissions;
use App\Modules\System\Models\Language;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables;
use Filament\Tables\Table;

class MenuItemResource extends Resource
{
    use AuthorizesWithPermissions;

    protected static ?string $model = MenuItem::class;

    protected static string $permissionPrefix = 'cms.menu';

    protected static string|BackedEnum|null $navigationIcon  = 'heroicon-o-bars-3';
    protected static string|\UnitEnum|null  $navigationGroup = 'CMS';
    protected static ?int                   $navigationSort  = 4;
    protected static ?string                $slug            = 'cms/menu';

    public static function getNavigationLabel(): string
    {
        return __('admin.cms.menu.nav');
    }

    public static function getModelLabel(): string
    {
        return __('admin.cms.menu.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.cms.menu.model_plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->schema([
                    Forms\Components\Select::make('type')
                        ->label(__('admin.cms.menu.field.type'))
                        ->options([
                            MenuItem::TYPE_HOME => __('admin.cms.menu.type.home'),
                            MenuItem::TYPE_PAGE => __('admin.cms.menu.type.page'),
                            MenuItem::TYPE_URL  => __('admin.cms.menu.type.url'),
                        ])
                        ->default(MenuItem::TYPE_URL)
                        ->required()
                        ->live(),

                    Forms\Components\Select::make('page_id')
                        ->label(__('admin.cms.menu.field.page'))
                        ->options(fn () => Page::query()
                            ->where('is_active', 1)
                            ->orderBy('title')
                            ->pluck('title', 'id'))
                        ->searchable()
                        ->required()
                        ->visible(fn (Get $get) => $get('type') === MenuItem::TYPE_PAGE),

                    Forms\Components\TextInput::make('url')
                        ->label(__('admin.cms.menu.field.url'))
                        ->maxLength(255)
                        ->required()
                        ->helperText(__('admin.cms.menu.field.url_hint'))
                        ->visible(fn (Get $get) => $get('type') === MenuItem::TYPE_URL),

                    Tabs::make()
                        ->columnSpanFull()
                        ->tabs(
                            Language::active()
                                ->map(fn (Language $lang) => Tab::make($lang->name)->schema([
                                    Forms\Components\TextInput::make("i18n.{$lang->code}.title")
                                        ->label(__('admin.cms.menu.field.title'))
                                        ->maxLength(191)
                                        ->required($lang->code === Language::default())
                                        ->columnSpanFull(),
                                ]))
                                ->all()
                        ),

                    Forms\Components\Toggle::make('new_tab')
                        ->label(__('admin.cms.menu.field.new_tab')),

                    Forms\Components\Toggle::make('is_active')
                        ->label(__('admin.cms.menu.field.is_active'))
                        ->default(true),
                ])
                ->columns(2)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('admin.cms.menu.col.title'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label(__('admin.cms.menu.col.type'))
                    ->badge()
                    ->formatStateUsing(fn (string $state) => __("admin.cms.menu.type.{$state}"))
                    ->color(fn (string $state) => match ($state) {
                        MenuItem::TYPE_HOME => 'success',
                        MenuItem::TYPE_PAGE => 'info',
                        default             => 'gray',
                    }),

                Tables\Columns\TextColumn::make('target')
                    ->label(__('admin.cms.menu.col.target'))
                    ->state(fn (MenuItem $record) => $record->resolvedUrl(Language::default()))
                    ->color('gray')
                    ->limit(40),

                Tables\Columns\IconColumn::make('new_tab')
                    ->label(__('admin.cms.menu.field.new_tab'))
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('admin.cms.menu.field.is_active'))
                    ->boolean(),
            ])
            ->recordAction('edit')
            ->actions([
                EditAction::make()->iconButton()->icon('heroicon-o-pencil-square')->modalWidth(Width::Large),
                DeleteAction::make()->iconButton()->icon('heroicon-o-trash'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenuItems::route('/'),
        ];
    }
}
