<?php

namespace App\Modules\Cms\Filament\Resources;

use App\Modules\Cms\Filament\Resources\FeedbackResource\Pages;
use App\Modules\Cms\Models\Feedback;
use App\Modules\System\Filament\Concerns\AuthorizesWithPermissions;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Заявки с формы обратной связи (таблица cms_feedback) — read-only список.
 * Создаются с сайта; в админке можно просмотреть, пометить обработанными, удалить.
 */
class FeedbackResource extends Resource
{
    use AuthorizesWithPermissions;

    protected static ?string $model = Feedback::class;

    protected static string $permissionPrefix = 'cms.feedback';

    protected static string|BackedEnum|null $navigationIcon  = 'heroicon-o-chat-bubble-left-right';
    protected static string|\UnitEnum|null  $navigationGroup = 'CMS';
    protected static ?int                   $navigationSort  = 4;
    protected static ?string                $slug            = 'cms/feedback';

    public static function getNavigationLabel(): string
    {
        return __('admin.feedback.nav');
    }

    public static function getModelLabel(): string
    {
        return __('admin.feedback.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.feedback.model_plural');
    }

    // Заявки приходят только с сайта — создавать вручную нельзя.
    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $pending = Feedback::where('is_processed', 0)->count();

        return $pending > 0 ? (string) $pending : null;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label(__('admin.feedback.col.name')),

                    Forms\Components\TextInput::make('email')
                        ->label(__('admin.feedback.col.email')),

                    Forms\Components\TextInput::make('ip_address')
                        ->label(__('admin.feedback.col.ip')),

                    Forms\Components\Placeholder::make('created_at')
                        ->label(__('admin.feedback.col.created_at'))
                        ->content(fn (?Feedback $record) => $record?->created_at),

                    Forms\Components\Textarea::make('message')
                        ->label(__('admin.feedback.col.message'))
                        ->rows(6)
                        ->columnSpanFull(),
                ])
                ->columns(2)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('admin.feedback.col.id'))
                    ->sortable()
                    ->width('60px'),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('admin.feedback.col.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label(__('admin.feedback.col.email'))
                    ->copyable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('message')
                    ->label(__('admin.feedback.col.message'))
                    ->limit(60)
                    ->tooltip(fn ($record) => $record->message)
                    ->searchable(),

                Tables\Columns\TextColumn::make('ip_address')
                    ->label(__('admin.feedback.col.ip'))
                    ->badge()
                    ->color('gray')
                    ->copyable()
                    ->placeholder('—')
                    ->toggleable()
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_processed')
                    ->label(__('admin.feedback.col.status'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-clock')
                    ->trueColor('success')
                    ->falseColor('warning')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('admin.feedback.col.created_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('is_processed')
                    ->label(__('admin.feedback.col.status'))
                    ->options([
                        '0' => __('admin.feedback.status.pending'),
                        '1' => __('admin.feedback.status.processed'),
                    ]),
            ])
            ->recordAction('view')
            ->actions([
                ViewAction::make()->iconButton()->icon('heroicon-o-eye')->modalWidth(Width::TwoExtraLarge),

                Action::make('toggleProcessed')
                    ->iconButton()
                    ->icon(fn (Feedback $record) => $record->is_processed ? 'heroicon-o-arrow-uturn-left' : 'heroicon-o-check')
                    ->color(fn (Feedback $record) => $record->is_processed ? 'gray' : 'success')
                    ->tooltip(fn (Feedback $record) => $record->is_processed
                        ? __('admin.feedback.action.unprocess')
                        : __('admin.feedback.action.process'))
                    ->action(fn (Feedback $record) => $record->update(['is_processed' => $record->is_processed ? 0 : 1])),

                DeleteAction::make()->iconButton()->icon('heroicon-o-trash'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeedback::route('/'),
        ];
    }
}
