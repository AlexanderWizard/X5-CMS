<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocsUserResource\Pages;
use App\Models\DocsUser;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class DocsUserResource extends Resource
{
    protected static ?string $model = DocsUser::class;

    protected static string|BackedEnum|null $navigationIcon  = 'heroicon-o-users';
    protected static string|\UnitEnum|null  $navigationGroup = 'System';
    protected static ?int                   $navigationSort  = 1;
    protected static ?string                $slug            = 'system/users';

    public static function getNavigationLabel(): string
    {
        return __('admin.users.nav');
    }

    public static function getModelLabel(): string
    {
        return __('admin.users.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.users.model_plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('login')
                ->label(__('admin.users.field.login'))
                ->required()
                ->maxLength(191)
                ->unique(ignoreRecord: true),

            Forms\Components\TextInput::make('password')
                ->label(__('admin.users.field.password'))
                ->password()
                ->revealable()
                ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                ->dehydrated(fn ($state) => filled($state))
                ->required(fn (string $operation) => $operation === 'create')
                ->helperText(__('admin.users.field.password_hint')),

            Forms\Components\Toggle::make('is_active')
                ->label(__('admin.users.field.active'))
                ->default(true)
                ->inline(false),

            Forms\Components\TextInput::make('failed_attempts')
                ->label(__('admin.users.field.attempts'))
                ->numeric()
                ->default(0)
                ->minValue(0)
                ->maxValue(5)
                ->helperText(__('admin.users.field.attempts_hint')),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('admin.users.col.id'))
                    ->sortable()
                    ->width('60px'),

                Tables\Columns\TextColumn::make('login')
                    ->label(__('admin.users.col.login'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('admin.users.col.active'))
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                Tables\Columns\TextColumn::make('failed_attempts')
                    ->label(__('admin.users.col.attempts'))
                    ->badge()
                    ->color(fn ($state) => $state >= 5 ? 'danger' : ($state > 0 ? 'warning' : 'success'))
                    ->sortable(),
            ])
            ->defaultSort('id', 'asc')
            ->recordUrl(fn (DocsUser $record) => static::getUrl('edit', ['record' => $record]))
            ->actions([
                Action::make('reset')
                    ->label(__('admin.users.action.unlock'))
                    ->icon('heroicon-o-lock-open')
                    ->color('warning')
                    ->visible(fn (DocsUser $record) => !$record->isActive() || $record->failed_attempts > 0)
                    ->requiresConfirmation()
                    ->action(fn (DocsUser $record) => $record->update([
                        'is_active'       => 1,
                        'failed_attempts' => 0,
                    ])),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDocsUsers::route('/'),
            'create' => Pages\CreateDocsUser::route('/create'),
            'edit'   => Pages\EditDocsUser::route('/{record}/edit'),
        ];
    }
}
