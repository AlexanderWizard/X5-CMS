<?php

namespace App\Filament\Pages;

use App\Modules\System\Filament\Widgets\DashboardOverviewWidget;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected static ?int $navigationSort = -1;

    public static function getNavigationLabel(): string
    {
        return __('admin.nav.dashboard');
    }

    public function getHeading(): string
    {
        return '';
    }

    public function getTitle(): string
    {
        return __('admin.nav.dashboard');
    }

    public function getWidgets(): array
    {
        return [
            DashboardOverviewWidget::class,
        ];
    }

    public function getColumns(): int|array
    {
        return 3;
    }
}
