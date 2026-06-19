<?php

namespace App\Modules\Blog\Filament\Resources;

use App\Modules\Blog\Filament\Resources\CategoryResource\Pages;
use App\Modules\Blog\Models\Category;
use App\Modules\System\Filament\Concerns\AuthorizesWithPermissions;
use App\Modules\System\Models\Language;
use BackedEnum;
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

class CategoryResource extends Resource
{
    use AuthorizesWithPermissions;

    protected static ?string $model = Category::class;

    protected static string $permissionPrefix = 'blog.categories';

    protected static string|BackedEnum|null $navigationIcon  = 'heroicon-o-folder';
    protected static string|\UnitEnum|null  $navigationGroup = 'Blog';
    protected static ?int                   $navigationSort  = 2;
    protected static ?string                $slug            = 'blog/categories';

    public static function getNavigationLabel(): string
    {
        return __('admin.blog.categories.nav');
    }

    public static function getModelLabel(): string
    {
        return __('admin.blog.categories.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.blog.categories.model_plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->schema([
                    Forms\Components\TextInput::make('slug')
                        ->label(__('admin.blog.categories.field.slug'))
                        ->required()
                        ->maxLength(191)
                        ->unique(ignoreRecord: true)
                        ->columnSpanFull(),

                    Tabs::make()
                        ->columnSpanFull()
                        ->tabs(
                            Language::active()
                                ->map(fn (Language $lang) => Tab::make($lang->name)->schema([
                                    self::nameField($lang->code),
                                ]))
                                ->all()
                        ),
                ])
                ->columnSpanFull(),
        ]);
    }

    /**
     * Поле «название» для одной локали (i18n.{loc}.name).
     * У локали по умолчанию — обязательно и генерирует slug.
     */
    protected static function nameField(string $loc): Forms\Components\TextInput
    {
        $field = Forms\Components\TextInput::make("i18n.{$loc}.name")
            ->label(__('admin.blog.categories.field.name'))
            ->maxLength(191)
            ->columnSpanFull();

        if ($loc === Language::default()) {
            $field
                ->required()
                ->live(debounce: 500)
                ->afterStateUpdated(function (string $operation, $state, Set $set) {
                    if ($operation === 'create') {
                        $set('slug', Str::slug($state));
                    }
                });
        }

        return $field;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('admin.blog.col.id'))
                    ->sortable()
                    ->width('60px'),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('admin.blog.categories.field.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label(__('admin.blog.col.slug'))
                    ->badge()
                    ->color('gray')
                    ->searchable(),

                Tables\Columns\TextColumn::make('articles_count')
                    ->label(__('admin.blog.categories.col.articles'))
                    ->counts('articles')
                    ->badge(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('admin.blog.col.created_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('name', 'asc')
            ->recordAction('edit')
            ->actions([
                EditAction::make()->iconButton()->icon('heroicon-o-pencil-square')->modalWidth(Width::Large),
                DeleteAction::make()->iconButton()->icon('heroicon-o-trash'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
        ];
    }
}
