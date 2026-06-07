<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetUserLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('admin')->user();

        if ($user && in_array($user->locale, ['ru', 'en'])) {
            App::setLocale($user->locale);
        }

        return $next($request);
    }
}
