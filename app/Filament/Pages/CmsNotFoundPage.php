<?php

namespace App\Filament\Pages;

/**
 * Корень модуля CMS — /admin/cms (нет index-маршрута, показываем 404).
 */
class CmsNotFoundPage extends NotFoundPage
{
    protected static ?string $slug = 'cms';
}
