<?php

namespace App\Filament\Pages;

use App\Modules\Api\Filament\Widgets\QueueStatsWidget;
use App\Modules\System\Filament\Widgets\SiteOverviewWidget;
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

    public function getWidgets(): array
    {
        return [
            SiteOverviewWidget::class,
            QueueStatsWidget::class,
        ];
    }

    public function getColumns(): int|array
    {
        return 3;
    }
}
