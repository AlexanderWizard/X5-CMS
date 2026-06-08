<?php

namespace App\Filament\Pages;

/**
 * Корень модуля API — /admin/api (нет index-маршрута, показываем 404).
 */
class ApiNotFoundPage extends NotFoundPage
{
    protected static ?string $slug = 'api';
}
