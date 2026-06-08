<?php

namespace App\Modules\System\Filament\Resources;

use App\Modules\System\Filament\Concerns\AuthorizesWithPermissions;
use App\Modules\System\Filament\Resources\RoleResource\Pages;
use App\Modules\System\Models\Role;
use App\Modules\System\Support\Permissions;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class RoleResource extends Resource
{
    use AuthorizesWithPermissions;

    protected static ?string $model = Role::class;

    protected static string $permissionPrefix = 'system.roles';

    protected static string|BackedEnum|null $navigationIcon  = 'heroicon-o-shield-check';
    protected static string|\UnitEnum|null  $navigationGroup = 'System';
    protected static ?int                   $navigationSort  = 2;
    protected static ?string                $slug            = 'system/roles';

    public static function getNavigationLabel(): string
    {
        return __('admin.roles.nav');
    }

    public static function getModelLabel(): string
    {
        return __('admin.roles.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.roles.model_plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('admin.roles.section.main'))
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label(__('admin.roles.field.name'))
                        ->required()
                        ->maxLength(191)
                        ->unique(ignoreRecord: true),

                    Forms\Components\TextInput::make('description')
                        ->label(__('admin.roles.field.description'))
                        ->maxLength(255),

                    Forms\Components\Select::make('users')
                        ->label(__('admin.roles.field.users'))
                        ->relationship('users', 'login')
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->helperText(__('admin.roles.field.users_hint')),
                ])
                ->columns(1),

            Section::make(__('admin.permissions.heading'))
                ->description(__('admin.permissions.hint'))
                ->icon('heroicon-o-key')
                ->schema(static::buildPermissionTree())
                ->columns(1),
        ]);
    }

    /**
     * Дерево прав: для каждого модуля — секция, внутри — список прав по ресурсам.
     *
     * @return array<int, \Filament\Schemas\Components\Component>
     */
    protected static function buildPermissionTree(): array
    {
        $moduleSections = [];

        foreach (Permissions::tree() as $moduleKey => $module) {
            $resourceLists = [];

            foreach ($module['resources'] as $resourceKey => $resourceLabel) {
                $group = Permissions::groupKey($moduleKey, $resourceKey);

                $resourceLists[] = Forms\Components\CheckboxList::make("permissions_tree.{$group}")
                    ->label($resourceLabel)
                    ->options(Permissions::abilityOptions($moduleKey, $resourceKey))
                    ->columns(4)
                    ->gridDirection('row')
                    ->bulkToggleable();
            }

            $moduleSections[] = Section::make($module['label'])
                ->schema($resourceLists)
                ->collapsible()
                ->compact()
                ->columns(1);
        }

        return $moduleSections;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('admin.roles.col.id'))
                    ->sortable()
                    ->width('60px'),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('admin.roles.col.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label(__('admin.roles.col.description'))
                    ->limit(60)
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('users_count')
                    ->label(__('admin.roles.col.users'))
                    ->counts('users')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('permissions_count')
                    ->label(__('admin.roles.col.permissions'))
                    ->badge()
                    ->color('primary')
                    ->state(fn (Role $record) => count($record->permissions ?? [])),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('admin.roles.col.created_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('id', 'asc')
            ->recordUrl(fn (Role $record) => static::getUrl('edit', ['record' => $record]));
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit'   => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
