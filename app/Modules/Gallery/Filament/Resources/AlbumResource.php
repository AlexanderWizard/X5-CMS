<?php

namespace App\Modules\Gallery\Filament\Resources;

use App\Modules\Gallery\Filament\Resources\AlbumResource\Pages;
use App\Modules\Gallery\Models\Album;
use App\Modules\System\Filament\Concerns\AuthorizesWithPermissions;
use App\Modules\System\Models\Language;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class AlbumResource extends Resource
{
    use AuthorizesWithPermissions;

    protected static ?string $model = Album::class;

    protected static string $permissionPrefix = 'gallery.albums';

    protected static string|BackedEnum|null $navigationIcon  = 'heroicon-o-rectangle-stack';
    protected static string|\UnitEnum|null  $navigationGroup = 'Gallery';
    protected static ?int                   $navigationSort  = 1;
    protected static ?string                $slug            = 'gallery/albums';

    public static function getNavigationLabel(): string
    {
        return __('admin.gallery.albums.nav');
    }

    public static function getModelLabel(): string
    {
        return __('admin.gallery.albums.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.gallery.albums.model_plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->schema([
                    Forms\Components\TextInput::make('slug')
                        ->label(__('admin.gallery.albums.field.slug'))
                        ->required()
                        ->maxLength(191)
                        ->unique(ignoreRecord: true),

                    Forms\Components\TextInput::make('sort_order')
                        ->label(__('admin.gallery.albums.field.sort_order'))
                        ->numeric()
                        ->default(0),

                    Forms\Components\Toggle::make('is_active')
                        ->label(__('admin.gallery.albums.field.is_active'))
                        ->default(true)
                        ->columnSpanFull(),

                    Tabs::make()
                        ->columnSpanFull()
                        ->tabs(
                            Language::active()
                                ->map(fn (Language $lang) => Tab::make($lang->name)
                                    ->schema(self::localeFields($lang->code)))
                                ->all()
                        ),
                ])
                ->columns(2)
                ->columnSpanFull(),
        ]);
    }

    /**
     * Переводимые поля альбома для одной локали.
     *
     * @return array<int, \Filament\Forms\Components\Field>
     */
    protected static function localeFields(string $loc): array
    {
        $title = Forms\Components\TextInput::make("i18n.{$loc}.title")
            ->label(__('admin.gallery.albums.field.title'))
            ->maxLength(191)
            ->columnSpanFull();

        if ($loc === Language::default()) {
            $title
                ->required()
                ->live(debounce: 500)
                ->afterStateUpdated(function (string $operation, $state, Set $set) {
                    if ($operation === 'create') {
                        $set('slug', Str::slug($state));
                    }
                });
        }

        return [
            $title,

            Forms\Components\Textarea::make("i18n.{$loc}.description")
                ->label(__('admin.gallery.albums.field.description'))
                ->rows(3)
                ->maxLength(1000)
                ->columnSpanFull(),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('admin.gallery.col.id'))
                    ->sortable()
                    ->width('60px'),

                Tables\Columns\TextColumn::make('title')
                    ->label(__('admin.gallery.albums.field.title'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label(__('admin.gallery.col.slug'))
                    ->badge()
                    ->color('gray')
                    ->searchable(),

                Tables\Columns\TextColumn::make('photos_count')
                    ->label(__('admin.gallery.albums.col.photos'))
                    ->badge()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('admin.gallery.albums.field.is_active'))
                    ->boolean(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('admin.gallery.albums.col.updated_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->placeholder('—'),
            ])
            ->defaultSort('sort_order', 'asc')
            ->recordAction('edit')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('admin.gallery.albums.field.is_active')),
            ])
            ->actions([
                Action::make('photos')
                    ->label('')
                    ->tooltip(__('admin.gallery.photos.model_plural'))
                    ->icon('heroicon-o-photo')
                    ->iconButton()
                    ->url(fn (Album $record) => PhotoResource::getUrl('index', ['tableFilters[album_id][value]' => $record->id])),

                Action::make('open')
                    ->label('')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->iconButton()
                    ->url(fn (Album $record) => $record->url)
                    ->openUrlInNewTab()
                    ->visible(fn (Album $record) => $record->is_active),

                EditAction::make()->iconButton()->icon('heroicon-o-pencil-square')->modalWidth(Width::TwoExtraLarge),
                DeleteAction::make()->iconButton()->icon('heroicon-o-trash'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAlbums::route('/'),
        ];
    }
}
