<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MessageQueueResource\Pages;
use App\Models\MessageQueue;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MessageQueueResource extends Resource
{
    protected static ?string $model = MessageQueue::class;

    protected static string|BackedEnum|null $navigationIcon  = 'heroicon-o-queue-list';
    protected static string|\UnitEnum|null  $navigationGroup = 'API';
    protected static ?int                   $navigationSort  = 2;
    protected static ?string                $slug            = 'api/messages';

    public static function getNavigationLabel(): string
    {
        return __('admin.nav.message_queue');
    }

    public static function getModelLabel(): string
    {
        return __('admin.queue.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.queue.model_plural');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('admin.queue.col.id'))
                    ->sortable()
                    ->width('80px'),

                Tables\Columns\TextColumn::make('channel')
                    ->label(__('admin.queue.col.channel'))
                    ->badge()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('body')
                    ->label(__('admin.queue.col.body'))
                    ->limit(100)
                    ->tooltip(fn ($record) => $record->body)
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_processed')
                    ->label(__('admin.queue.col.status'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-clock')
                    ->trueColor('success')
                    ->falseColor('warning')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('admin.queue.col.created_at'))
                    ->dateTime('d.m.Y H:i:s')
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('is_processed')
                    ->label(__('admin.queue.filter.status'))
                    ->options([
                        '0' => __('admin.queue.status.pending'),
                        '1' => __('admin.queue.status.processed'),
                    ]),

                Tables\Filters\SelectFilter::make('channel')
                    ->label(__('admin.queue.filter.channel'))
                    ->options(fn () => MessageQueue::query()
                        ->distinct()
                        ->orderBy('channel')
                        ->pluck('channel', 'channel')
                        ->toArray()
                    ),
            ])
            ->poll('30s');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMessageQueues::route('/'),
        ];
    }
}
