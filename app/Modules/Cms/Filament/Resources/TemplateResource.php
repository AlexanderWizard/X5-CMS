<?php

namespace App\Modules\Cms\Filament\Resources;

use App\Modules\Cms\Filament\Resources\TemplateResource\Pages;
use App\Modules\Cms\Models\Template;
use App\Modules\System\Filament\Concerns\AuthorizesWithPermissions;
use BackedEnum;
use Filament\Actions\ReplicateAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
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

                    Forms\Components\Toggle::make('is_default')
                        ->label(__('admin.cms.templates.field.default'))
                        ->helperText(__('admin.cms.templates.field.default_hint'))
                        ->inline(false),
                ])
                ->columns(2),

            Section::make(__('admin.cms.templates.section.body'))
                ->columnSpanFull()
                ->schema([
                    Forms\Components\Textarea::make('body')
                        ->label(__('admin.cms.templates.field.body'))
                        ->rows(24)
                        ->extraInputAttributes(['style' => 'min-height: 24rem; font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-size: 0.8rem;'])
                        ->columnSpanFull(),

                    Forms\Components\Placeholder::make('body_hint')
                        ->hiddenLabel()
                        ->content(static::buildBodyHint())
                        ->columnSpanFull(),
                ]),
        ])->columns(1);
    }

    /**
     * Красивая шпаргалка по доступным переменным и директивам шаблона.
     */
    protected static function buildBodyHint(): HtmlString
    {
        $vars = [
            '$title'    => __('admin.cms.templates.vars.title'),
            '$content'  => __('admin.cms.templates.vars.content'),
            '$children' => __('admin.cms.templates.vars.children'),
            '$page'     => __('admin.cms.templates.vars.page'),
            '$appName'  => __('admin.cms.templates.vars.appname'),
        ];

        $chip = 'display:inline-flex;align-items:baseline;gap:.4rem;padding:.3rem .6rem;background:#fff;border:1px solid #e5e7eb;border-radius:.5rem;font-size:.8rem;line-height:1.2;';
        $code = 'font-family:ui-monospace,SFMono-Regular,Menlo,monospace;color:#ea580c;font-weight:600;';
        $muted = 'color:#6b7280;';

        $items = '';
        foreach ($vars as $name => $desc) {
            $items .= "<span style=\"{$chip}\"><code style=\"{$code}\">"
                . e($name) . "</code><span style=\"{$muted}\">" . e($desc) . "</span></span>";
        }

        $snippet = 'font-family:ui-monospace,SFMono-Regular,Menlo,monospace;background:#0f172a;color:#e2e8f0;padding:.5rem .7rem;border-radius:.5rem;font-size:.8rem;display:inline-block;';

        $heading = e(__('admin.cms.templates.vars.heading'));
        $outLabel = e(__('admin.cms.templates.vars.output'));
        $incLabel = e(__('admin.cms.templates.vars.include'));

        return new HtmlString(<<<HTML
            <div style="display:flex;flex-direction:column;gap:.75rem;">
                <div style="font-weight:600;color:#374151;font-size:.82rem;text-transform:uppercase;letter-spacing:.04em;">
                    {$heading}
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:.5rem;">{$items}</div>
                <div style="display:flex;flex-wrap:wrap;gap:1rem;align-items:center;">
                    <span style="color:#6b7280;font-size:.8rem;">{$outLabel}:</span>
                    <code style="{$snippet}">{!! \$content !!}</code>
                    <span style="color:#6b7280;font-size:.8rem;">{$incLabel}:</span>
                    <code style="{$snippet}">@partial('header')</code>
                </div>
            </div>
        HTML);
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

                Tables\Columns\IconColumn::make('is_default')
                    ->label(__('admin.cms.templates.col.default'))
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
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
            ->recordUrl(fn (Template $record) => static::getUrl('edit', ['record' => $record]))
            ->actions([
                ReplicateAction::make()
                    ->label(__('admin.cms.templates.action.clone'))
                    ->tooltip(__('admin.cms.templates.action.clone'))
                    ->icon('heroicon-o-document-duplicate')
                    ->iconButton()
                    ->color('gray')
                    ->visible(fn () => static::canCreate())
                    ->excludeAttributes(['id', 'created_at', 'pages_count'])
                    ->beforeReplicaSaved(function (Template $replica, Template $record): void {
                        $replica->name       = $record->name . ' (копия)';
                        $replica->slug       = static::uniqueSlug($record->slug);
                        $replica->is_system  = false;
                        $replica->is_default = false;
                    })
                    ->successRedirectUrl(fn (Template $replica) => static::getUrl('edit', ['record' => $replica])),
            ]);
    }

    /**
     * Сгенерировать уникальный slug на основе исходного (base-copy, base-copy-2, …).
     */
    protected static function uniqueSlug(string $base): string
    {
        $slug = $base . '-copy';
        $i    = 1;

        while (Template::where('slug', $slug)->exists()) {
            $i++;
            $slug = $base . '-copy-' . $i;
        }

        return $slug;
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
