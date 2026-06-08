<?php

namespace App\Modules\System\Filament\Resources;

use App\Modules\System\Filament\Resources\RoleResource\Pages;
use App\Modules\System\Models\Role;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static string|BackedEnum|null $navigationIcon  = 'heroicon-o-shield-check';
    protected static string|\UnitEnum|null  $navigationGroup = 'System';
    protected static ?int                   $navigationSort  = 2;
    protected static ?string                $slug            = 'system/roles';

    public static function getNavigationLabel(): string
    {
        return __('admin.roles.nav');
    }

    public static function getModelLabel(): string
    {
        return __('admin.roles.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.roles.model_plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('name')
                ->label(__('admin.roles.field.name'))
                ->required()
                ->maxLength(191)
                ->unique(ignoreRecord: true),

            Forms\Components\TextInput::make('description')
                ->label(__('admin.roles.field.description'))
                ->maxLength(255),

            Forms\Components\Select::make('users')
                ->label(__('admin.roles.field.users'))
                ->relationship('users', 'login')
                ->multiple()
                ->preload()
                ->searchable()
                ->helperText(__('admin.roles.field.users_hint')),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('admin.roles.col.id'))
                    ->sortable()
                    ->width('60px'),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('admin.roles.col.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label(__('admin.roles.col.description'))
                    ->limit(60)
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('users_count')
                    ->label(__('admin.roles.col.users'))
                    ->counts('users')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('admin.roles.col.created_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('id', 'asc')
            ->recordUrl(fn (Role $record) => static::getUrl('edit', ['record' => $record]));
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit'   => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
