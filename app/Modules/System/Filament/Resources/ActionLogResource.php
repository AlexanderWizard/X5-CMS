<?php

namespace App\Modules\System\Filament\Resources;

use App\Modules\System\Filament\Concerns\AuthorizesWithPermissions;
use App\Modules\System\Filament\Resources\ActionLogResource\Pages;
use App\Modules\System\Models\ActionLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ActionLogResource extends Resource
{
    use AuthorizesWithPermissions;

    protected static ?string $model = ActionLog::class;

    protected static string $permissionPrefix = 'system.actions';

    protected static string|BackedEnum|null $navigationIcon  = 'heroicon-o-clipboard-document-list';
    protected static string|\UnitEnum|null  $navigationGroup = 'System';
    protected static ?int                   $navigationSort  = 4;
    protected static ?string                $slug            = 'system/actions';

    public static function getNavigationLabel(): string
    {
        return __('admin.actions.nav');
    }

    public static function getModelLabel(): string
    {
        return __('admin.actions.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.actions.model_plural');
    }

    /** Журнал — только для чтения. */
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('admin.actions.col.created_at'))
                    ->dateTime('d.m.Y H:i:s')
                    ->sortable(),

                Tables\Columns\TextColumn::make('user_login')
                    ->label(__('admin.actions.col.user'))
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('event')
                    ->label(__('admin.actions.col.event'))
                    ->badge()
                    ->formatStateUsing(fn (string $state) => __("admin.actions.event.{$state}"))
                    ->color(fn (string $state) => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        'login'   => 'info',
                        default   => 'gray',
                    }),

                Tables\Columns\TextColumn::make('subject_label')
                    ->label(__('admin.actions.col.subject'))
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('subject_id')
                    ->label(__('admin.actions.col.subject_id'))
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('properties')
                    ->label(__('admin.actions.col.details'))
                    ->state(function (ActionLog $record): string {
                        $changed = $record->properties['changed'] ?? null;

                        return $changed ? implode(', ', $changed) : '—';
                    })
                    ->wrap(),

                Tables\Columns\TextColumn::make('ip_address')
                    ->label(__('admin.actions.col.ip'))
                    ->placeholder('—')
                    ->toggleable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('event')
                    ->label(__('admin.actions.col.event'))
                    ->options([
                        'created' => __('admin.actions.event.created'),
                        'updated' => __('admin.actions.event.updated'),
                        'deleted' => __('admin.actions.event.deleted'),
                        'login'   => __('admin.actions.event.login'),
                    ]),

                Tables\Filters\SelectFilter::make('user_login')
                    ->label(__('admin.actions.col.user'))
                    ->options(fn () => ActionLog::query()
                        ->whereNotNull('user_login')
                        ->distinct()
                        ->orderBy('user_login')
                        ->pluck('user_login', 'user_login')
                        ->toArray()
                    ),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActionLogs::route('/'),
        ];
    }
}
