<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AlleenBeheerder
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user()?->isBeheerder(), 403, 'Alleen voor kantinebeheer.');

        return $next($request);
    }
}
