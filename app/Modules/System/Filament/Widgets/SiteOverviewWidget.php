<?php

namespace App\Modules\System\Filament\Widgets;

use App\Modules\Cms\Models\Block;
use App\Modules\Cms\Models\Page;
use App\Modules\Cms\Models\Template;
use App\Modules\System\Models\ActionLog;
use App\Modules\System\Models\FirewallRule;
use App\Modules\System\Models\Setting;
use App\Modules\System\Models\Translation;
use App\Modules\System\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Обзор общего состояния сайта на дашборде: контент, пользователи,
 * активность (спарклайн за 7 дней), статусы обслуживания и файрвола.
 */
class SiteOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 0;

    protected int|string|array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        $pagesActive = Page::where('is_active', 1)->count();
        $usersActive = User::where('is_active', 1)->count();
        $maintenance = Setting::bool('maintenance_mode');
        $fwRules     = FirewallRule::where('is_active', 1)->count();
        $translationKeys = Translation::query()->distinct()->count('key');

        // Активность за 7 дней + спарклайн по дням
        $actions7 = 0;
        $chart = [];
        for ($i = 6; $i >= 0; $i--) {
            $count = ActionLog::whereDate('created_at', now()->subDays($i)->toDateString())->count();
            $chart[] = $count;
            $actions7 += $count;
        }

        return [
            Stat::make(__('admin.dash.pages'), Page::count())
                ->description(__('admin.dash.published', ['n' => $pagesActive]))
                ->descriptionIcon('heroicon-m-check-circle')
                ->icon('heroicon-o-document-text')
                ->color('primary'),

            Stat::make(__('admin.dash.templates'), Template::count())
                ->icon('heroicon-o-rectangle-group')
                ->color('gray'),

            Stat::make(__('admin.dash.blocks'), Block::count())
                ->icon('heroicon-o-squares-2x2')
                ->color('gray'),

            Stat::make(__('admin.dash.users'), User::count())
                ->description(__('admin.dash.active', ['n' => $usersActive]))
                ->descriptionIcon('heroicon-m-user')
                ->icon('heroicon-o-users')
                ->color('primary'),

            Stat::make(__('admin.dash.translations'), $translationKeys)
                ->icon('heroicon-o-language')
                ->color('gray'),

            Stat::make(__('admin.dash.actions'), $actions7)
                ->icon('heroicon-o-clipboard-document-list')
                ->color('info')
                ->chart($chart),

            Stat::make(
                __('admin.dash.maintenance'),
                $maintenance ? __('admin.dash.on') : __('admin.dash.off')
            )
                ->icon('heroicon-o-wrench-screwdriver')
                ->color($maintenance ? 'danger' : 'success'),

            Stat::make(
                __('admin.dash.firewall'),
                $fwRules > 0 ? $fwRules : __('admin.dash.fw_open')
            )
                ->description($fwRules > 0
                    ? __('admin.dash.fw_rules', ['n' => $fwRules])
                    : __('admin.dash.fw_open_desc'))
                ->icon('heroicon-o-shield-check')
                ->color($fwRules > 0 ? 'info' : 'gray'),
        ];
    }
}
