<?php

namespace App\Modules\Cms\Filament\Resources;

use App\Modules\Cms\Filament\Resources\TemplateResource\Pages;
use App\Modules\Cms\Models\Template;
use App\Modules\System\Filament\Concerns\AuthorizesWithPermissions;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class TemplateResource extends Resource
{
    use AuthorizesWithPermissions;

    protected static ?string $model = Template::class;

    protected static string $permissionPrefix = 'cms.templates';

    protected static string|BackedEnum|null $navigationIcon  = 'heroicon-o-code-bracket-square';
    protected static string|\UnitEnum|null  $navigationGroup = 'CMS';
    protected static ?int                   $navigationSort  = 2;
    protected static ?string                $slug            = 'cms/templates';

    public static function getNavigationLabel(): string
    {
        return __('admin.cms.templates.nav');
    }

    public static function getModelLabel(): string
    {
        return __('admin.cms.templates.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.cms.templates.model_plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('admin.cms.templates.section.main'))
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label(__('admin.cms.templates.field.name'))
                        ->required()
                        ->maxLength(191)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (string $operation, $state, Set $set) {
                            if ($operation === 'create') {
                                $set('slug', Str::slug($state));
                            }
                        }),

                    Forms\Components\TextInput::make('slug')
                        ->label(__('admin.cms.templates.field.slug'))
                        ->required()
                        ->maxLength(191)
                        ->unique(ignoreRecord: true),

                    Forms\Components\Toggle::make('is_system')
                        ->label(__('admin.cms.templates.field.system'))
                        ->helperText(__('admin.cms.templates.field.system_hint'))
                        ->inline(false),
                ])
                ->columns(2),

            Section::make(__('admin.cms.templates.section.body'))
                ->description(__('admin.cms.templates.field.body_hint'))
                ->schema([
                    Forms\Components\Textarea::make('body')
                        ->label(__('admin.cms.templates.field.body'))
                        ->rows(24)
                        ->extraInputAttributes(['style' => 'font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-size: 0.8rem;'])
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('admin.cms.templates.col.id'))
                    ->sortable()
                    ->width('60px'),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('admin.cms.templates.col.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label(__('admin.cms.templates.col.slug'))
                    ->badge()
                    ->color('gray')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_system')
                    ->label(__('admin.cms.templates.col.system'))
                    ->boolean()
                    ->trueIcon('heroicon-o-lock-closed')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('warning')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('pages_count')
                    ->label(__('admin.cms.templates.col.pages'))
                    ->counts('pages')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('admin.cms.templates.col.created_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('id', 'asc')
            ->recordUrl(fn (Template $record) => static::getUrl('edit', ['record' => $record]));
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTemplates::route('/'),
            'create' => Pages\CreateTemplate::route('/create'),
            'edit'   => Pages\EditTemplate::route('/{record}/edit'),
        ];
    }
}
