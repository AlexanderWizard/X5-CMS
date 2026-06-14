<?php

namespace App\Http\Middleware;

use App\Modules\System\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Режим обслуживания публичного сайта.
 *
 * Если в настройках включён maintenance_mode — посетители видят страницу
 * обслуживания (503). Авторизованные администраторы (guard admin) видят
 * сайт как обычно, чтобы можно было проверить контент.
 */
class CheckMaintenance
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Setting::bool('maintenance_mode') && ! auth('admin')->check()) {
            return response()->view('cms.maintenance', [
                'siteName' => Setting::get('site_name', config('app.name', 'Site')),
                'message'  => Setting::get('maintenance_message', 'Сайт временно на обслуживании.'),
            ], 503);
        }

        return $next($request);
    }
}
