<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Pages\Auth\Login;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Blade;
use App\Http\Middleware\CheckFirewall;
use App\Http\Middleware\SetUserLocale;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->profile(EditProfile::class)
            ->authGuard('admin')

            // «Профиль» в меню пользователя открывает модалку (ProfileModal),
            // а не отдельную страницу: пункт меню диспатчит Livewire-событие,
            // по которому глобальный компонент монтирует экшен с формой.
            ->userMenuItems([
                'profile' => Action::make('profile')
                    ->label(__('admin.profile.nav'))
                    ->icon('heroicon-o-user-circle')
                    // no-op ->action() обязателен: иначе пункт без url И без action
                    // user-menu рендерит как НЕкликабельный заголовок ($hasProfileHeader).
                    // alpineClickHandler ставит x-on:click и снимает дефолтный
                    // wire:click="mountAction" (livewireClickHandlerEnabled(false)).
                    // Без url() рендерится <button> (без SPA wire:navigate). Диспатч —
                    // глобально через window.Livewire (не $wire: он вне scope после teleport).
                    ->action(fn () => null)
                    ->alpineClickHandler("window.Livewire.dispatch('open-profile-modal', { returnUrl: window.location.href })")
                    ->sort(-1),
            ])

            // Брендинг
            ->brandName('X5-CMS')
            ->brandLogo(fn (): string => asset('images/logo.svg') . '?v=' . filemtime(public_path('images/logo.svg')))
            ->darkModeBrandLogo(fn (): string => asset('images/logo.svg') . '?v=' . filemtime(public_path('images/logo.svg')))
            ->brandLogoHeight('2rem')
            ->favicon(asset('images/favicon-32.png') . '?v=' . filemtime(public_path('images/favicon-32.png')))
            ->colors(['primary' => Color::Orange])
            ->maxContentWidth(Width::Full)

            // SPA-навигация (HTML5 History / wire:navigate) — без полной перезагрузки страниц
            ->spa()

            // Сайдбар
            ->sidebarCollapsibleOnDesktop()
            ->collapsibleNavigationGroups()

            // Кастомный CSS (тёмный сайдбар, стиль таблиц)
            ->renderHook(
                PanelsRenderHook::STYLES_AFTER,
                fn (): HtmlString => new HtmlString(
                    '<link rel="stylesheet" href="' . asset('css/admin.css') . '?v=' . filemtime(public_path('css/admin.css')) . '">'
                ),
            )

            // Авто-сворачивание неактивных групп меню (раскрыта только активная)
            ->renderHook(
                PanelsRenderHook::SCRIPTS_AFTER,
                fn (): HtmlString => new HtmlString(<<<'HTML'
                    <script>
                        (function () {
                            function syncSidebarGroups() {
                                var store = window.Alpine && window.Alpine.store('sidebar');
                                if (! store) return;

                                var groups = document.querySelectorAll(
                                    '.fi-main-sidebar .fi-sidebar-group[data-group-label]'
                                );

                                var collapsed = [];
                                groups.forEach(function (group) {
                                    if (! group.classList.contains('fi-active')) {
                                        collapsed.push(group.getAttribute('data-group-label'));
                                    }
                                });

                                store.collapsedGroups = collapsed;
                            }

                            document.addEventListener('alpine:initialized', function () {
                                requestAnimationFrame(syncSidebarGroups);
                            });
                            document.addEventListener('livewire:navigated', function () {
                                requestAnimationFrame(syncSidebarGroups);
                            });
                        })();
                    </script>
                HTML),
            )

            // AJAX-лоадер: центрированный спиннер при загрузке модалки.
            // Оверлей (position:fixed) + скрипт ниже, который ловит Livewire-запрос
            // монтирования экшена (mountAction — кнопки страницы вроде Create;
            // mountTableAction — Edit/Delete в строке). Стиль — admin.scss.
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): HtmlString => new HtmlString(<<<'HTML'
                    <div class="app-modal-loader" aria-hidden="true">
                        <div class="app-modal-loader__spinner"></div>
                    </div>
                HTML),
            )

            // Глобальный компонент с модалкой профиля (триггер — пункт меню «Профиль»).
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): HtmlString => new HtmlString(Blade::render('@livewire(\'profile-modal\')')),
            )
            ->renderHook(
                PanelsRenderHook::SCRIPTS_AFTER,
                fn (): HtmlString => new HtmlString(<<<'HTML'
                    <script>
                        document.addEventListener('livewire:init', function () {
                            if (! window.Livewire) return;

                            var MOUNT = ['mountAction', 'mountTableAction', 'mountFormComponentAction'];
                            var opensModal = function (payload) {
                                try {
                                    // payload приходит JSON-строкой — разбираем
                                    if (typeof payload === 'string') payload = JSON.parse(payload);
                                    var comps = Array.isArray(payload) ? payload : (payload.components || []);
                                    return comps.some(function (c) {
                                        return (c.calls || []).some(function (call) {
                                            return MOUNT.indexOf(call.method) !== -1;
                                        });
                                    });
                                } catch (e) { return false; }
                            };

                            Livewire.hook('request', function (opts) {
                                if (! opensModal(opts.payload)) return;

                                // Показываем оверлей только если открытие дольше 200мс —
                                // на быстрых ответах он вообще не мелькает.
                                var timer = setTimeout(function () {
                                    var l = document.querySelector('.app-modal-loader');
                                    if (l) l.style.display = 'flex';
                                }, 200);

                                var hide = function () {
                                    clearTimeout(timer);
                                    var l = document.querySelector('.app-modal-loader');
                                    if (l) l.style.display = 'none';
                                };

                                opts.succeed && opts.succeed(hide);
                                opts.fail && opts.fail(hide);
                            });
                        });
                    </script>
                HTML),
            )

            // Внешняя ссылка на документацию в левом меню
            ->navigationItems([
                NavigationItem::make(__('admin.nav.documentation'))
                    ->icon('heroicon-o-document-text')
                    ->url('/docs', shouldOpenInNewTab: true)
                    ->sort(0),
            ])

            // Авто-обнаружение ресурсов, страниц, виджетов (модульная архитектура)
            ->discoverResources(in: app_path('Modules/Api/Filament/Resources'), for: 'App\Modules\Api\Filament\Resources')
            ->discoverResources(in: app_path('Modules/Cms/Filament/Resources'), for: 'App\Modules\Cms\Filament\Resources')
            ->discoverResources(in: app_path('Modules/Blog/Filament/Resources'), for: 'App\Modules\Blog\Filament\Resources')
            ->discoverResources(in: app_path('Modules/System/Filament/Resources'), for: 'App\Modules\System\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->discoverPages(in: app_path('Modules/System/Filament/Pages'), for: 'App\Modules\System\Filament\Pages')
            ->discoverWidgets(in: app_path('Modules/Api/Filament/Widgets'), for: 'App\Modules\Api\Filament\Widgets')
            ->discoverWidgets(in: app_path('Modules/Cms/Filament/Widgets'), for: 'App\Modules\Cms\Filament\Widgets')
            ->discoverWidgets(in: app_path('Modules/System/Filament/Widgets'), for: 'App\Modules\System\Filament\Widgets')

            ->middleware([
                CheckFirewall::class,
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                SetUserLocale::class,
            ]);
    }
}
