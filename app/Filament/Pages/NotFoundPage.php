<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

/**
 * Базовая страница 404 внутри лейаута админ-панели.
 *
 * Используется для корневых URL модулей (групп навигации), у которых нет
 * собственного index-маршрута — например /admin/api, /admin/system.
 *
 * Чтобы добавить новый модульный корень — создайте наследника и задайте $slug:
 *
 *     class ApiNotFoundPage extends NotFoundPage
 *     {
 *         protected static ?string $slug = 'api';
 *     }
 */
abstract class NotFoundPage extends Page
{
    protected string $view = 'filament.pages.not-found';

    protected static bool $shouldRegisterNavigation = false;

    public function getTitle(): string
    {
        return '404';
    }
}
