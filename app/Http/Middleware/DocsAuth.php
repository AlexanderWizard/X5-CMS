<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DocsAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->get('docs_authenticated')) {
            return redirect()->route('docs.login');
        }

        return $next($request);
    }
}
