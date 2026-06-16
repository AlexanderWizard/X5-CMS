<?php

namespace App\Modules\System\Filament\Resources;

use App\Modules\System\Filament\Concerns\AuthorizesWithPermissions;
use App\Modules\System\Filament\Resources\LanguageResource\Pages;
use App\Modules\System\Models\Language;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables;
use Filament\Tables\Table;

class LanguageResource extends Resource
{
    use AuthorizesWithPermissions;

    protected static ?string $model = Language::class;

    protected static string $permissionPrefix = 'system.languages';

    protected static string|BackedEnum|null $navigationIcon  = 'heroicon-o-globe-alt';
    protected static string|\UnitEnum|null  $navigationGroup = 'System';
    protected static ?int                   $navigationSort  = 6;
    protected static ?string                $slug            = 'system/languages';

    public static function getNavigationLabel(): string
    {
        return __('admin.languages.nav');
    }

    public static function getModelLabel(): string
    {
        return __('admin.languages.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.languages.model_plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('code')
                ->label(__('admin.languages.field.code'))
                ->required()
                ->maxLength(5)
                ->unique(ignoreRecord: true)
                ->placeholder('en, ru, de')
                ->helperText(__('admin.languages.field.code_hint')),

            Forms\Components\TextInput::make('name')
                ->label(__('admin.languages.field.name'))
                ->required()
                ->maxLength(64)
                ->placeholder('English, Русский'),

            Forms\Components\TextInput::make('sort_order')
                ->label(__('admin.languages.field.sort'))
                ->numeric()
                ->default(0),

            Forms\Components\Toggle::make('is_default')
                ->label(__('admin.languages.field.default'))
                ->helperText(__('admin.languages.field.default_hint'))
                ->default(false)
                ->inline(false),

            Forms\Components\Toggle::make('is_active')
                ->label(__('admin.languages.field.active'))
                ->default(true)
                ->inline(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('admin.languages.col.id'))
                    ->sortable()
                    ->width('60px'),

                Tables\Columns\TextColumn::make('code')
                    ->label(__('admin.languages.col.code'))
                    ->badge()
                    ->color('primary')
                    ->copyable()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('admin.languages.col.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_default')
                    ->label(__('admin.languages.col.default'))
                    ->boolean()
                    ->trueColor('warning')
                    ->falseColor('gray'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('admin.languages.col.active'))
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('admin.languages.col.sort'))
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('admin.languages.col.created_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('sort_order', 'asc')
            // Редактирование во всплывающем модальном окне (без перехода на страницу).
            ->recordAction('edit')
            ->actions([
                EditAction::make()->iconButton()->icon('heroicon-o-pencil-square')->modalWidth(Width::TwoExtraLarge),
                // Язык по умолчанию удалять нельзя (защита от потери дефолтной локали).
                DeleteAction::make()->iconButton()->icon('heroicon-o-trash')
                    ->visible(fn (Language $record) => ! $record->is_default),
            ]);
    }

    public static function getPages(): array
    {
        // create/edit — в модалках, поэтому страницы create/edit НЕ регистрируем
        // (иначе Filament навесил бы переход по URL вместо модалки).
        return [
            'index' => Pages\ListLanguages::route('/'),
        ];
    }
}
