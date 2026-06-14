<?php

namespace App\Modules\System\Filament\Resources;

use App\Modules\System\Filament\Concerns\AuthorizesWithPermissions;
use App\Modules\System\Filament\Resources\TranslationResource\Pages;
use App\Modules\System\Models\Translation;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class TranslationResource extends Resource
{
    use AuthorizesWithPermissions;

    protected static ?string $model = Translation::class;

    protected static string $permissionPrefix = 'system.translations';

    protected static string|BackedEnum|null $navigationIcon  = 'heroicon-o-language';
    protected static string|\UnitEnum|null  $navigationGroup = 'System';
    protected static ?int                   $navigationSort  = 5;
    protected static ?string                $slug            = 'system/translations';

    /** Доступные локали интерфейса. */
    public const LOCALES = [
        'ru' => 'Русский (ru)',
        'en' => 'English (en)',
    ];

    public static function getNavigationLabel(): string
    {
        return __('admin.translations.nav');
    }

    public static function getModelLabel(): string
    {
        return __('admin.translations.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.translations.model_plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('group')
                ->label(__('admin.translations.field.group'))
                ->required()
                ->maxLength(64)
                ->default('admin'),

            Forms\Components\Select::make('locale')
                ->label(__('admin.translations.field.locale'))
                ->options(self::LOCALES)
                ->required()
                ->native(false),

            Forms\Components\TextInput::make('key')
                ->label(__('admin.translations.field.key'))
                ->required()
                ->maxLength(191)
                ->placeholder('users.nav'),

            Forms\Components\Textarea::make('value')
                ->label(__('admin.translations.field.value'))
                ->rows(3)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('admin.translations.col.id'))
                    ->sortable()
                    ->width('60px'),

                Tables\Columns\TextColumn::make('locale')
                    ->label(__('admin.translations.col.locale'))
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                Tables\Columns\TextColumn::make('group')
                    ->label(__('admin.translations.col.group'))
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                Tables\Columns\TextColumn::make('key')
                    ->label(__('admin.translations.col.key'))
                    ->fontFamily('mono')
                    ->copyable()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextInputColumn::make('value')
                    ->label(__('admin.translations.col.value'))
                    ->searchable()
                    ->rules(['nullable', 'string']),
            ])
            ->defaultSort('key', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('locale')
                    ->label(__('admin.translations.filter.locale'))
                    ->options(self::LOCALES),

                Tables\Filters\SelectFilter::make('group')
                    ->label(__('admin.translations.filter.group'))
                    ->options(fn () => Translation::query()
                        ->distinct()
                        ->orderBy('group')
                        ->pluck('group', 'group')
                        ->toArray()
                    ),
            ])
            ->recordUrl(fn (Translation $record) => static::getUrl('edit', ['record' => $record]));
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTranslations::route('/'),
            'create' => Pages\CreateTranslation::route('/create'),
            'edit'   => Pages\EditTranslation::route('/{record}/edit'),
        ];
    }
}
