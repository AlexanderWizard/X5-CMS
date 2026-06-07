<?php

namespace App\Modules\Api\Filament\Widgets;

use App\Modules\Api\Models\MessageQueue;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class QueueStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $total     = MessageQueue::count();
        $pending   = MessageQueue::where('is_processed', 0)->count();
        $processed = MessageQueue::where('is_processed', 1)->count();

        return [
            Stat::make(__('admin.stats.total'), $total)
                ->icon('heroicon-o-envelope')
                ->color('gray'),

            Stat::make(__('admin.stats.pending'), $pending)
                ->icon('heroicon-o-clock')
                ->color($pending > 0 ? 'warning' : 'success'),

            Stat::make(__('admin.stats.processed'), $processed)
                ->icon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }
}
