<?php

namespace App\Http\Middleware;

use App\Modules\Cms\Models\Redirect;
use App\Modules\System\Models\Language;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 301/302-редиректы по таблице `redirects`.
 *
 * Работает внутри языковой группы: путь вида «{locale}/{rest}» разбирается,
 * остаток {rest} (без локали) ищется в активных правилах. Цель отдаётся под
 * той же локалью: /{locale}/{to_path} (или /{locale} для пустого to_path).
 *
 * Подключён ПЕРВЫМ в группе (до CheckMaintenance) — старый URL уводит на новый
 * даже в режиме обслуживания.
 */
class HandleRedirects
{
    public function handle(Request $request, Closure $next): Response
    {
        $path     = trim($request->path(), '/');
        $segments = explode('/', $path);
        $locale   = $segments[0] ?? '';

        // Реагируем только на локализованные URL публичного сайта.
        if ($locale === '' || ! Language::isValid($locale)) {
            return $next($request);
        }

        $rest = trim(substr($path, strlen($locale)), '/');

        $rule = Redirect::activeMap()[$rest] ?? null;

        if ($rule && $rule->to_path !== $rest) {
            // Счётчик попаданий (необязательный, без падений на проблемах с БД).
            $rule->newQuery()->whereKey($rule->id)->increment('hits');

            $target = $rule->to_path === ''
                ? url($locale)
                : url($locale . '/' . $rule->to_path);

            return redirect()->to($target, $rule->status);
        }

        return $next($request);
    }
}
