<?php

namespace App\Modules\Gallery\Filament\Resources;

use App\Modules\Gallery\Filament\Resources\PhotoResource\Pages;
use App\Modules\Gallery\Models\Album;
use App\Modules\Gallery\Models\Photo;
use App\Modules\Gallery\Support\PhotoUploader;
use App\Modules\System\Filament\Concerns\AuthorizesWithPermissions;
use App\Modules\System\Models\Language;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class PhotoResource extends Resource
{
    use AuthorizesWithPermissions;

    protected static ?string $model = Photo::class;

    protected static string $permissionPrefix = 'gallery.photos';

    protected static string|BackedEnum|null $navigationIcon  = 'heroicon-o-photo';
    protected static string|\UnitEnum|null  $navigationGroup = 'Gallery';
    protected static ?int                   $navigationSort  = 2;
    protected static ?string                $slug            = 'gallery/photos';

    public static function getNavigationLabel(): string
    {
        return __('admin.gallery.photos.nav');
    }

    public static function getModelLabel(): string
    {
        return __('admin.gallery.photos.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.gallery.photos.model_plural');
    }

    public static function form(Schema $schema): Schema
    {
        // Форма редактирования одной фотографии (без загрузки файла — файл
        // привязан при загрузке; меняются только мета-данные).
        return $schema->components([
            Section::make()
                ->schema([
                    Forms\Components\Select::make('album_id')
                        ->label(__('admin.gallery.photos.field.album'))
                        ->relationship('album', 'title')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\TextInput::make('tags')
                        ->label(__('admin.gallery.photos.field.tags'))
                        ->helperText(__('admin.gallery.photos.field.tags_hint'))
                        ->maxLength(250),

                    Forms\Components\TextInput::make('sort_order')
                        ->label(__('admin.gallery.photos.field.sort_order'))
                        ->numeric()
                        ->default(0),

                    Forms\Components\Toggle::make('is_active')
                        ->label(__('admin.gallery.photos.field.is_active'))
                        ->default(true),

                    Tabs::make()
                        ->columnSpanFull()
                        ->tabs(
                            Language::active()
                                ->map(fn (Language $lang) => Tab::make($lang->name)->schema([
                                    Forms\Components\TextInput::make("i18n.{$lang->code}.title")
                                        ->label(__('admin.gallery.photos.field.title'))
                                        ->maxLength(250)
                                        ->columnSpanFull(),
                                ]))
                                ->all()
                        ),
                ])
                ->columns(2)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumb_url')
                    ->label(__('admin.gallery.photos.col.preview'))
                    ->square()
                    ->height(48),

                Tables\Columns\TextColumn::make('id')
                    ->label(__('admin.gallery.col.id'))
                    ->sortable()
                    ->width('60px'),

                Tables\Columns\TextColumn::make('album.title')
                    ->label(__('admin.gallery.photos.field.album'))
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label(__('admin.gallery.photos.field.title'))
                    ->limit(40)
                    ->placeholder('—')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tags')
                    ->label(__('admin.gallery.photos.field.tags'))
                    ->limit(30)
                    ->placeholder('—')
                    ->searchable(),

                Tables\Columns\TextColumn::make('camera')
                    ->label(__('admin.gallery.photos.col.camera'))
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('taken_at')
                    ->label(__('admin.gallery.photos.col.taken_at'))
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('admin.gallery.photos.field.is_active'))
                    ->boolean(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('album_id')
                    ->label(__('admin.gallery.photos.field.album'))
                    ->relationship('album', 'title')
                    ->searchable()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('admin.gallery.photos.field.is_active')),
            ])
            ->recordAction('edit')
            ->actions([
                Action::make('open')
                    ->label('')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->iconButton()
                    ->url(fn (Photo $record) => $record->url)
                    ->openUrlInNewTab()
                    ->visible(fn (Photo $record) => $record->is_active),
                EditAction::make()->iconButton()->icon('heroicon-o-pencil-square')->modalWidth(Width::Large),
                DeleteAction::make()->iconButton()->icon('heroicon-o-trash'),
            ]);
    }

    /**
     * Экшен массовой загрузки фото в альбом (заголовок списка).
     * Каждый загруженный файл проходит через PhotoUploader: создаётся запись,
     * генерируются варианты и считывается EXIF, временный файл удаляется.
     */
    public static function uploadAction(): Action
    {
        return Action::make('upload')
            ->label(__('admin.gallery.photos.action.upload'))
            ->icon('heroicon-o-arrow-up-tray')
            ->modalWidth(Width::Large)
            ->schema([
                Forms\Components\Select::make('album_id')
                    ->label(__('admin.gallery.photos.field.album'))
                    ->options(fn () => Album::query()->orderBy('sort_order')->pluck('title', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\FileUpload::make('files')
                    ->label(__('admin.gallery.photos.field.files'))
                    ->image()
                    ->multiple()
                    ->disk('gallery')
                    ->directory('_tmp')
                    ->visibility('public')
                    ->maxSize(20480)
                    ->maxFiles(50)
                    ->required(),
            ])
            ->action(function (array $data): void {
                $album = Album::find($data['album_id']);

                if (!$album) {
                    return;
                }

                $count = 0;

                foreach ((array) ($data['files'] ?? []) as $relative) {
                    $absolute = Storage::disk('gallery')->path($relative);

                    if (PhotoUploader::ingest($album, $absolute)) {
                        $count++;
                    }

                    // Временный исходник больше не нужен — варианты уже сгенерированы.
                    Storage::disk('gallery')->delete($relative);
                }

                Notification::make()
                    ->success()
                    ->title(__('admin.gallery.photos.uploaded', ['count' => $count]))
                    ->send();
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPhotos::route('/'),
        ];
    }
}
