<?php

namespace App\Modules\Cms\Filament\Resources;

use App\Modules\Cms\Filament\Resources\BlockResource\Pages;
use App\Modules\Cms\Models\Block;
use App\Modules\System\Filament\Concerns\AuthorizesWithPermissions;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BlockResource extends Resource
{
    use AuthorizesWithPermissions;

    protected static ?string $model = Block::class;

    protected static string $permissionPrefix = 'cms.blocks';

    protected static string|BackedEnum|null $navigationIcon  = 'heroicon-o-rectangle-group';
    protected static string|\UnitEnum|null  $navigationGroup = 'CMS';
    protected static ?int                   $navigationSort  = 3;
    protected static ?string                $slug            = 'cms/blocks';

    public static function getNavigationLabel(): string
    {
        return __('admin.cms.blocks.nav');
    }

    public static function getModelLabel(): string
    {
        return __('admin.cms.blocks.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.cms.blocks.model_plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('admin.cms.blocks.section.main'))
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label(__('admin.cms.blocks.field.name'))
                        ->required()
                        ->maxLength(191)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (string $operation, $state, Set $set) {
                            if ($operation === 'create') {
                                $set('slug', Str::slug($state));
                            }
                        }),

                    Forms\Components\TextInput::make('slug')
                        ->label(__('admin.cms.blocks.field.slug'))
                        ->required()
                        ->maxLength(191)
                        ->unique(ignoreRecord: true)
                        ->helperText(__('admin.cms.blocks.field.slug_hint')),

                    Forms\Components\Textarea::make('value')
                        ->label(__('admin.cms.blocks.field.value'))
                        ->rows(3)
                        ->columnSpanFull(),
                ])
                ->columns(2)
                // секция должна занимать всю ширину формы (в модалке корневая
                // сетка 2-колоночная — без этого секция «прижимается» к левой половине)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('admin.cms.blocks.col.id'))
                    ->sortable()
                    ->width('60px'),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('admin.cms.blocks.col.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label(__('admin.cms.blocks.col.slug'))
                    ->badge()
                    ->color('gray')
                    ->copyable()
                    ->formatStateUsing(fn (string $state) => "@block('{$state}')")
                    ->searchable(),

                Tables\Columns\TextColumn::make('value')
                    ->label(__('admin.cms.blocks.col.value'))
                    ->limit(60)
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('admin.cms.blocks.col.created_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('id', 'asc')
            // Редактирование во всплывающем модальном окне (клик по строке или кнопка).
            ->recordAction('edit')
            ->actions([
                EditAction::make()->modalWidth(Width::TwoExtraLarge),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        // create/edit — в модалках, поэтому соответствующие страницы НЕ регистрируем:
        // иначе Filament навесил бы на экшены переход по URL вместо модалки
        // (имена экшенов create/edit совпали бы с именами страниц).
        return [
            'index' => Pages\ListBlocks::route('/'),
        ];
    }
}
