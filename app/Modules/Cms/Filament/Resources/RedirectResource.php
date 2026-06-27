<?php

namespace App\Modules\Cms\Filament\Resources;

use App\Modules\Cms\Filament\Resources\RedirectResource\Pages;
use App\Modules\Cms\Models\Redirect;
use App\Modules\System\Filament\Concerns\AuthorizesWithPermissions;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables;
use Filament\Tables\Table;

class RedirectResource extends Resource
{
    use AuthorizesWithPermissions;

    protected static ?string $model = Redirect::class;

    protected static string $permissionPrefix = 'cms.redirects';

    protected static string|BackedEnum|null $navigationIcon  = 'heroicon-o-arrow-right-circle';
    protected static string|\UnitEnum|null  $navigationGroup = 'CMS';
    protected static ?int                   $navigationSort  = 6;
    protected static ?string                $slug            = 'cms/redirects';

    public static function getNavigationLabel(): string
    {
        return __('admin.cms.redirects.nav');
    }

    public static function getModelLabel(): string
    {
        return __('admin.cms.redirects.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.cms.redirects.model_plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->schema([
                    Forms\Components\TextInput::make('from_path')
                        ->label(__('admin.cms.redirects.field.from'))
                        ->helperText(__('admin.cms.redirects.field.from_hint'))
                        ->required()
                        ->maxLength(191)
                        ->unique(ignoreRecord: true)
                        ->prefix('/{lang}/')
                        ->dehydrateStateUsing(fn (?string $state) => trim((string) $state, '/')),

                    Forms\Components\TextInput::make('to_path')
                        ->label(__('admin.cms.redirects.field.to'))
                        ->helperText(__('admin.cms.redirects.field.to_hint'))
                        ->maxLength(191)
                        ->prefix('/{lang}/')
                        ->dehydrateStateUsing(fn (?string $state) => trim((string) $state, '/')),

                    Forms\Components\Select::make('status')
                        ->label(__('admin.cms.redirects.field.status'))
                        ->options([
                            301 => __('admin.cms.redirects.status.301'),
                            302 => __('admin.cms.redirects.status.302'),
                        ])
                        ->default(301)
                        ->required(),

                    Forms\Components\Toggle::make('is_active')
                        ->label(__('admin.cms.redirects.field.is_active'))
                        ->default(true),
                ])
                ->columns(2)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('from_path')
                    ->label(__('admin.cms.redirects.col.from'))
                    ->formatStateUsing(fn (string $state) => '/' . $state)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('arrow')
                    ->label('')
                    ->icon('heroicon-o-arrow-right')
                    ->color('gray'),

                Tables\Columns\TextColumn::make('to_path')
                    ->label(__('admin.cms.redirects.col.to'))
                    ->formatStateUsing(fn (?string $state) => '/' . (string) $state)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('admin.cms.redirects.col.status'))
                    ->badge()
                    ->color(fn (int $state) => $state === 301 ? 'success' : 'warning'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('admin.cms.redirects.col.is_active'))
                    ->boolean(),

                Tables\Columns\TextColumn::make('hits')
                    ->label(__('admin.cms.redirects.col.hits'))
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('admin.cms.redirects.col.created_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->recordAction('edit')
            ->actions([
                EditAction::make()->iconButton()->icon('heroicon-o-pencil-square')->modalWidth(Width::TwoExtraLarge),
                DeleteAction::make()->iconButton()->icon('heroicon-o-trash'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRedirects::route('/'),
        ];
    }
}
