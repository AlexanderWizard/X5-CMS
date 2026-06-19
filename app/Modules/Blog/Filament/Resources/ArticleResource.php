<?php

namespace App\Modules\Blog\Filament\Resources;

use App\Modules\Blog\Filament\Resources\ArticleResource\Pages;
use App\Modules\Blog\Models\Article;
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
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ArticleResource extends Resource
{
    use AuthorizesWithPermissions;

    protected static ?string $model = Article::class;

    protected static string $permissionPrefix = 'blog.articles';

    protected static string|BackedEnum|null $navigationIcon  = 'heroicon-o-newspaper';
    protected static string|\UnitEnum|null  $navigationGroup = 'Blog';
    protected static ?int                   $navigationSort  = 1;
    protected static ?string                $slug            = 'blog/articles';

    public static function getNavigationLabel(): string
    {
        return __('admin.blog.articles.nav');
    }

    public static function getModelLabel(): string
    {
        return __('admin.blog.articles.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.blog.articles.model_plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            // Общие (непереводимые) параметры статьи
            Section::make()
                ->schema([
                    Forms\Components\TextInput::make('slug')
                        ->label(__('admin.blog.articles.field.slug'))
                        ->required()
                        ->maxLength(191)
                        ->unique(ignoreRecord: true),

                    Forms\Components\Select::make('category_id')
                        ->label(__('admin.blog.articles.field.category'))
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable(),

                    Forms\Components\Select::make('tags')
                        ->label(__('admin.blog.articles.field.tags'))
                        ->relationship('tags', 'name')
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('image')
                        ->label(__('admin.blog.articles.field.image'))
                        ->maxLength(255)
                        ->url()
                        ->columnSpanFull(),

                    Forms\Components\DateTimePicker::make('published_at')
                        ->label(__('admin.blog.articles.field.published_at'))
                        ->seconds(false)
                        ->native(false),

                    Forms\Components\Toggle::make('is_published')
                        ->label(__('admin.blog.articles.field.is_published'))
                        ->default(true),
                ])
                ->columns(2)
                ->columnSpanFull(),

            // Переводимый контент — по вкладке на каждый активный язык
            Tabs::make()
                ->columnSpanFull()
                ->tabs(
                    Language::active()
                        ->map(fn (Language $lang) => Tab::make($lang->name)
                            ->schema(self::localeFields($lang->code)))
                        ->all()
                ),
        ]);
    }

    /**
     * Переводимые поля статьи для одной локали (i18n.{loc}.*).
     * У локали по умолчанию заголовок обязателен и генерирует slug.
     *
     * @return array<int, \Filament\Forms\Components\Field>
     */
    protected static function localeFields(string $loc): array
    {
        $title = Forms\Components\TextInput::make("i18n.{$loc}.title")
            ->label(__('admin.blog.articles.field.title'))
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

            Forms\Components\Textarea::make("i18n.{$loc}.excerpt")
                ->label(__('admin.blog.articles.field.excerpt'))
                ->rows(2)
                ->maxLength(500)
                ->columnSpanFull(),

            Forms\Components\RichEditor::make("i18n.{$loc}.content")
                ->label(__('admin.blog.articles.field.content'))
                ->columnSpanFull(),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('admin.blog.col.id'))
                    ->sortable()
                    ->width('60px'),

                Tables\Columns\TextColumn::make('title')
                    ->label(__('admin.blog.articles.col.title'))
                    ->searchable()
                    ->sortable()
                    ->limit(60),

                Tables\Columns\TextColumn::make('category.name')
                    ->label(__('admin.blog.articles.col.category'))
                    ->badge()
                    ->color('gray')
                    ->placeholder('—'),

                Tables\Columns\IconColumn::make('is_published')
                    ->label(__('admin.blog.articles.field.is_published'))
                    ->boolean(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label(__('admin.blog.articles.col.published_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->placeholder('—'),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label(__('admin.blog.articles.field.category'))
                    ->relationship('category', 'name'),

                Tables\Filters\TernaryFilter::make('is_published')
                    ->label(__('admin.blog.articles.field.is_published')),
            ])
            ->actions([
                Action::make('open')
                    ->label('')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->iconButton()
                    ->url(fn (Article $record) => $record->url)
                    ->openUrlInNewTab()
                    ->visible(fn (Article $record) => $record->is_published),
                EditAction::make()->iconButton()->icon('heroicon-o-pencil-square'),
                DeleteAction::make()->iconButton()->icon('heroicon-o-trash'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit'   => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
