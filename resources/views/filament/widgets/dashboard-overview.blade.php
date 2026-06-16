@php
    $channelMeta = [
        'email'    => ['icon' => 'heroicon-o-envelope',          'color' => '#f97316'],
        'sms'      => ['icon' => 'heroicon-o-chat-bubble-bottom-center-text', 'color' => '#28c76f'],
        'push'     => ['icon' => 'heroicon-o-bell-alert',        'color' => '#fb923c'],
        'telegram' => ['icon' => 'heroicon-o-paper-airplane',    'color' => '#00cfe8'],
    ];
@endphp

<x-filament-widgets::widget>
    <div class="x5-dash">

        {{-- Ряд 1: приветствие + сводка --}}
        <div class="x5-row x5-row--top">

            {{-- Приветствие --}}
            <div class="x5-card x5-card--hello">
                <div class="x5-hello__text">
                    <h3 class="x5-hello__title">С возвращением, {{ $login }}! 🎉</h3>
                    <p class="x5-hello__sub">Сообщений в очереди обработано</p>
                    <div class="x5-hello__big">{{ $progress }}%</div>
                    <p class="x5-hello__note">{{ $msgProcessed }} из {{ $msgTotal }} сообщений 🚀</p>
                    <a href="{{ \App\Modules\Api\Filament\Resources\MessageQueueResource::getUrl() }}" class="x5-btn">
                        К очереди
                    </a>
                </div>
                <div class="x5-hello__art">🏆</div>
            </div>

            {{-- Сводка по контенту --}}
            <div class="x5-card x5-card--summary">
                <div class="x5-card__head">
                    <h3 class="x5-card__title">Состояние сайта</h3>
                    <span class="x5-card__hint">Контент и доступ за всё время</span>
                </div>
                <div class="x5-summary__grid">
                    @php
                        $tiles = [
                            ['Страницы',  $pages,        'heroicon-o-document-text',   '#f97316'],
                            ['Пользователи', $users,     'heroicon-o-users',           '#28c76f'],
                            ['Шаблоны',   $templates,    'heroicon-o-rectangle-group', '#fb923c'],
                            ['Блоки',     $blocks,       'heroicon-o-squares-2x2',     '#00cfe8'],
                            ['Переводы',  $translations, 'heroicon-o-language',        '#f97316'],
                            ['Действий',  $actionsTotal, 'heroicon-o-clipboard-document-list', '#ea5455'],
                        ];
                    @endphp
                    @foreach ($tiles as [$label, $value, $icon, $color])
                        <div class="x5-summary__item">
                            <span class="x5-summary__icon" style="--c: {{ $color }}">
                                <x-filament::icon :icon="$icon" class="w-5 h-5" />
                            </span>
                            <div>
                                <div class="x5-summary__label">{{ $label }}</div>
                                <div class="x5-summary__value">{{ $value }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Ряд 2: график активности + очередь по каналам --}}
        <div class="x5-row x5-row--bottom">

            {{-- Недельный график --}}
            <div class="x5-card x5-card--chart">
                <div class="x5-card__head">
                    <h3 class="x5-card__title">Активность за неделю</h3>
                    <span class="x5-card__hint">Записей в журнале действий</span>
                </div>
                <div class="x5-chart">
                    @foreach ($week as $d)
                        @php $h = max(6, (int) round($d['value'] / $weekMax * 100)); @endphp
                        <div class="x5-chart__col">
                            <div class="x5-chart__bar-wrap">
                                <div class="x5-chart__bar" style="height: {{ $h }}%" title="{{ $d['value'] }}"></div>
                            </div>
                            <span class="x5-chart__label">{{ $d['label'] }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="x5-chart__foot">
                    <span class="x5-chart__delta {{ $weekDelta >= 0 ? 'is-up' : 'is-down' }}">
                        {{ $weekDelta >= 0 ? '▲' : '▼' }} {{ abs($weekDelta) }}%
                    </span>
                    <span class="x5-chart__foot-text">{{ $weekSum }} действий за 7 дней относительно прошлой недели</span>
                </div>
            </div>

            {{-- Очередь по каналам --}}
            <div class="x5-card x5-card--channels">
                <div class="x5-card__head">
                    <h3 class="x5-card__title">Очередь по каналам</h3>
                    <span class="x5-card__hint">Обработано / всего</span>
                </div>
                <div class="x5-channels">
                    @forelse ($channels as $c)
                        @php $m = $channelMeta[$c['channel']] ?? ['icon' => 'heroicon-o-inbox', 'color' => '#a8aaae']; @endphp
                        <div class="x5-channel">
                            <span class="x5-channel__icon" style="--c: {{ $m['color'] }}">
                                <x-filament::icon :icon="$m['icon']" class="w-5 h-5" />
                            </span>
                            <div class="x5-channel__body">
                                <div class="x5-channel__row">
                                    <span class="x5-channel__name">{{ ucfirst($c['channel']) }}</span>
                                    <span class="x5-channel__count">{{ $c['done'] }}/{{ $c['total'] }}</span>
                                </div>
                                <div class="x5-channel__track">
                                    <div class="x5-channel__fill" style="width: {{ $c['percent'] }}%; background: {{ $m['color'] }}"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="x5-empty">Очередь пуста</p>
                    @endforelse
                </div>
            </div>

            {{-- Статусы: режим обслуживания + файрвол --}}
            <div class="x5-status-col">
                <div class="x5-card x5-card--status">
                    <span class="x5-status__icon" style="--c: {{ $maintenance ? '#ea5455' : '#28c76f' }}">
                        <x-filament::icon icon="heroicon-o-wrench-screwdriver" class="w-5 h-5" />
                    </span>
                    <div class="x5-status__label">Режим обслуживания</div>
                    <div class="x5-status__value" style="color: {{ $maintenance ? '#ea5455' : '#28c76f' }}">
                        {{ $maintenance ? 'Включён' : 'Выключен' }}
                    </div>
                </div>
                <div class="x5-card x5-card--status">
                    <span class="x5-status__icon" style="--c: {{ $firewall > 0 ? '#00cfe8' : '#a8aaae' }}">
                        <x-filament::icon icon="heroicon-o-shield-check" class="w-5 h-5" />
                    </span>
                    <div class="x5-status__label">Файрвол</div>
                    <div class="x5-status__value">
                        {{ $firewall > 0 ? $firewall . ' прав.' : 'Открыт' }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-filament-widgets::widget>
