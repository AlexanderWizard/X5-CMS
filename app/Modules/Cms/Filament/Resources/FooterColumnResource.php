<?php

namespace App\Modules\Cms\Filament\Resources;

use App\Modules\Cms\Filament\Resources\FooterColumnResource\Pages;
use App\Modules\Cms\Models\FooterColumn;
use App\Modules\Cms\Models\FooterLink;
use App\Modules\Cms\Models\Page;
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
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class FooterColumnResource extends Resource
{
    use AuthorizesWithPermissions;

    protected static ?string $model = FooterColumn::class;

    protected static string $permissionPrefix = 'cms.footer';

    protected static string|BackedEnum|null $navigationIcon  = 'heroicon-o-view-columns';
    protected static string|\UnitEnum|null  $navigationGroup = 'CMS';
    protected static ?int                   $navigationSort  = 5;
    protected static ?string                $slug            = 'cms/footer';

    public static function getNavigationLabel(): string
    {
        return __('admin.cms.footer.nav');
    }

    public static function getModelLabel(): string
    {
        return __('admin.cms.footer.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.cms.footer.model_plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->schema([
                    Tabs::make()
                        ->columnSpanFull()
                        ->tabs(
                            Language::active()
                                ->map(fn (Language $lang) => Tab::make($lang->name)->schema([
                                    Forms\Components\TextInput::make("i18n.{$lang->code}.title")
                                        ->label(__('admin.cms.footer.field.title'))
                                        ->maxLength(191)
                                        ->required($lang->code === Language::default())
                                        ->columnSpanFull(),
                                ]))
                                ->all()
                        ),

                    Forms\Components\Toggle::make('is_active')
                        ->label(__('admin.cms.footer.field.is_active'))
                        ->default(true),
                ])
                ->columnSpanFull(),

            Section::make(__('admin.cms.footer.links.label'))
                ->schema([
                    Forms\Components\Repeater::make('links')
                        ->hiddenLabel()
                        ->relationship()
                        ->orderColumn('sort_order')
                        ->reorderable()
                        ->collapsible()
                        ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                        ->addActionLabel(__('admin.cms.footer.links.add'))
                        ->defaultItems(0)
                        ->schema(self::linkFields())
                        ->columns(2),
                ])
                ->columnSpanFull(),
        ])->columns(1);
    }

    /**
     * Поля одной ссылки внутри Repeater.
     *
     * @return array<int, \Filament\Forms\Components\Field|\Filament\Schemas\Components\Component>
     */
    protected static function linkFields(): array
    {
        return [
            Forms\Components\Select::make('type')
                ->label(__('admin.cms.footer.links.type'))
                ->options([
                    FooterLink::TYPE_HOME => __('admin.cms.footer.type.home'),
                    FooterLink::TYPE_PAGE => __('admin.cms.footer.type.page'),
                    FooterLink::TYPE_URL  => __('admin.cms.footer.type.url'),
                ])
                ->default(FooterLink::TYPE_URL)
                ->required()
                ->live(),

            Forms\Components\Select::make('page_id')
                ->label(__('admin.cms.footer.links.page'))
                ->options(fn () => Page::query()
                    ->where('is_active', 1)
                    ->orderBy('title')
                    ->pluck('title', 'id'))
                ->searchable()
                ->required()
                ->visible(fn (Get $get) => $get('type') === FooterLink::TYPE_PAGE),

            Forms\Components\TextInput::make('url')
                ->label(__('admin.cms.footer.links.url'))
                ->maxLength(255)
                ->required()
                ->helperText(__('admin.cms.footer.links.url_hint'))
                ->visible(fn (Get $get) => $get('type') === FooterLink::TYPE_URL),

            Tabs::make()
                ->columnSpanFull()
                ->tabs(
                    Language::active()
                        ->map(fn (Language $lang) => Tab::make($lang->name)->schema([
                            Forms\Components\TextInput::make("i18n.{$lang->code}.title")
                                ->label(__('admin.cms.footer.links.title'))
                                ->maxLength(191)
                                ->required($lang->code === Language::default())
                                ->columnSpanFull(),
                        ]))
                        ->all()
                ),

            Forms\Components\Toggle::make('new_tab')
                ->label(__('admin.cms.footer.links.new_tab')),

            Forms\Components\Toggle::make('is_active')
                ->label(__('admin.cms.footer.links.is_active'))
                ->default(true),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('admin.cms.footer.field.title'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('links_count')
                    ->label(__('admin.cms.footer.col.links'))
                    ->counts('links')
                    ->badge(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('admin.cms.footer.field.is_active'))
                    ->boolean(),
            ])
            ->actions([
                EditAction::make()->iconButton()->icon('heroicon-o-pencil-square'),
                DeleteAction::make()->iconButton()->icon('heroicon-o-trash'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFooterColumns::route('/'),
            'create' => Pages\CreateFooterColumn::route('/create'),
            'edit'   => Pages\EditFooterColumn::route('/{record}/edit'),
        ];
    }
}
