<?php

namespace App\Filament\Pages;

/**
 * Корень модуля System — /admin/system (нет index-маршрута, показываем 404).
 */
class SystemNotFoundPage extends NotFoundPage
{
    protected static ?string $slug = 'system';
}
