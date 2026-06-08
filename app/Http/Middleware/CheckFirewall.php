<?php

namespace App\Http\Middleware;

use App\Modules\System\Models\FirewallRule;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ограничивает доступ к админ-панели по списку разрешённых IP (файрвол).
 *
 * Если активных правил нет — доступ открыт. Иначе IP клиента должен совпасть
 * хотя бы с одним правилом (точный адрес или CIDR-подсеть).
 */
class CheckFirewall
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!FirewallRule::isAllowed($request->ip())) {
            abort(403, __('admin.firewall.denied'));
        }

        return $next($request);
    }
}
