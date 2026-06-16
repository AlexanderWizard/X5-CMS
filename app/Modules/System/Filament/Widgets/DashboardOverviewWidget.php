<?php

namespace App\Modules\System\Filament\Widgets;

use App\Modules\Api\Models\MessageQueue;
use App\Modules\Cms\Models\Block;
use App\Modules\Cms\Models\Page;
use App\Modules\Cms\Models\Template;
use App\Modules\System\Models\ActionLog;
use App\Modules\System\Models\FirewallRule;
use App\Modules\System\Models\Setting;
use App\Modules\System\Models\Translation;
use App\Modules\System\Models\User;
use Filament\Widgets\Widget;

/**
 * Карточный дашборд: приветствие, сводка по сайту, недельный график
 * активности, очередь по каналам. Данные — реальные (страницы, пользователи,
 * очередь сообщений, журнал действий).
 * Вёрстка — resources/views/filament/widgets/dashboard-overview.blade.php,
 * стили — блок .x5-dash в resources/scss/admin.scss.
 */
class DashboardOverviewWidget extends Widget
{
    protected static ?int $sort = -10;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.widgets.dashboard-overview';

    protected function getViewData(): array
    {
        $user = auth('admin')->user();

        $msgTotal     = MessageQueue::count();
        $msgProcessed = MessageQueue::where('is_processed', 1)->count();
        $progress     = $msgTotal > 0 ? (int) round($msgProcessed / $msgTotal * 100) : 0;

        // Недельная активность (журнал действий) — для столбчатого графика
        $week    = [];
        $weekSum = 0;
        for ($i = 6; $i >= 0; $i--) {
            $day   = now()->subDays($i);
            $count = ActionLog::whereDate('created_at', $day->toDateString())->count();
            $week[] = [
                'label' => $day->isoFormat('dd'),
                'value' => $count,
            ];
            $weekSum += $count;
        }
        $weekMax = max(1, max(array_column($week, 'value')));

        // Активность за прошлую неделю — для сравнения в процентах
        $prevSum = ActionLog::whereBetween('created_at', [
            now()->subDays(13)->startOfDay(),
            now()->subDays(7)->endOfDay(),
        ])->count();
        $weekDelta = $prevSum > 0
            ? (int) round(($weekSum - $prevSum) / $prevSum * 100)
            : ($weekSum > 0 ? 100 : 0);

        // Очередь по каналам — список с прогрессом обработки
        $channels = MessageQueue::selectRaw('channel, COUNT(*) AS total, SUM(is_processed) AS done')
            ->groupBy('channel')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($r) => [
                'channel' => $r->channel,
                'total'   => (int) $r->total,
                'done'    => (int) $r->done,
                'percent' => $r->total > 0 ? (int) round($r->done / $r->total * 100) : 0,
            ])
            ->all();

        return [
            'login'        => $user?->login ?? 'admin',
            'msgTotal'     => $msgTotal,
            'msgProcessed' => $msgProcessed,
            'progress'     => $progress,
            'pages'        => Page::count(),
            'users'        => User::count(),
            'templates'    => Template::count(),
            'blocks'       => Block::count(),
            'translations' => Translation::query()->distinct()->count('key'),
            'actionsTotal' => ActionLog::count(),
            'week'         => $week,
            'weekMax'      => $weekMax,
            'weekSum'      => $weekSum,
            'weekDelta'    => $weekDelta,
            'channels'     => $channels,
            'maintenance'  => Setting::bool('maintenance_mode'),
            'firewall'     => FirewallRule::where('is_active', 1)->count(),
        ];
    }
}
