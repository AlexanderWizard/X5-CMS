<?php

namespace App\Modules\System\Filament\Resources;

use App\Modules\System\Filament\Concerns\AuthorizesWithPermissions;
use App\Modules\System\Filament\Resources\FirewallResource\Pages;
use App\Modules\System\Models\FirewallRule;
use BackedEnum;
use Closure;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class FirewallResource extends Resource
{
    use AuthorizesWithPermissions;

    protected static ?string $model = FirewallRule::class;

    protected static string $permissionPrefix = 'system.firewall';

    protected static string|BackedEnum|null $navigationIcon  = 'heroicon-o-shield-exclamation';
    protected static string|\UnitEnum|null  $navigationGroup = 'System';
    protected static ?int                   $navigationSort  = 3;
    protected static ?string                $slug            = 'system/firewall';

    public static function getNavigationLabel(): string
    {
        return __('admin.firewall.nav');
    }

    public static function getModelLabel(): string
    {
        return __('admin.firewall.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.firewall.model_plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('ip_address')
                ->label(__('admin.firewall.field.ip'))
                ->required()
                ->maxLength(64)
                ->placeholder('192.168.1.10  /  10.0.0.0/24  /  ::1')
                ->helperText(__('admin.firewall.field.ip_hint'))
                ->rule(static function () {
                    return static function (string $attribute, $value, Closure $fail): void {
                        if (!FirewallRule::isValidIpOrCidr((string) $value)) {
                            $fail(__('admin.firewall.invalid'));
                        }
                    };
                }),

            Forms\Components\TextInput::make('description')
                ->label(__('admin.firewall.field.description'))
                ->maxLength(255),

            Forms\Components\Toggle::make('is_active')
                ->label(__('admin.firewall.field.active'))
                ->default(true)
                ->inline(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('admin.firewall.col.id'))
                    ->sortable()
                    ->width('60px'),

                Tables\Columns\TextColumn::make('ip_address')
                    ->label(__('admin.firewall.col.ip'))
                    ->badge()
                    ->color('primary')
                    ->copyable()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label(__('admin.firewall.col.description'))
                    ->limit(60)
                    ->placeholder('—'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('admin.firewall.col.active'))
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('admin.firewall.col.created_at'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('id', 'asc')
            ->recordUrl(fn (FirewallRule $record) => static::getUrl('edit', ['record' => $record]));
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFirewallRules::route('/'),
            'create' => Pages\CreateFirewallRule::route('/create'),
            'edit'   => Pages\EditFirewallRule::route('/{record}/edit'),
        ];
    }
}
