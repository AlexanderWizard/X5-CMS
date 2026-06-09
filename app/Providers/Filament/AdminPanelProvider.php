<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Pages\Auth\Login;
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

            // Брендинг
            ->brandName('Notify Service')
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

            // Внешняя ссылка на документацию в левом меню
            ->navigationItems([
                NavigationItem::make(__('admin.nav.documentation'))
                    ->icon('heroicon-o-document-text')
                    ->url('/docs', shouldOpenInNewTab: true)
                    ->sort(0),
            ])

            // Авто-обнаружение ресурсов, страниц, виджетов (модульная архитектура)
            ->discoverResources(in: app_path('Modules/Api/Filament/Resources'), for: 'App\Modules\Api\Filament\Resources')
            ->discoverResources(in: app_path('Modules/System/Filament/Resources'), for: 'App\Modules\System\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->discoverWidgets(in: app_path('Modules/Api/Filament/Widgets'), for: 'App\Modules\Api\Filament\Widgets')
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
