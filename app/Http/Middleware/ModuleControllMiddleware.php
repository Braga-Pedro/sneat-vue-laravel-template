<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ModuleControllMiddleware
{
    public function handle(Request $request, Closure $next, string $module): Response
    {
        if (!$request->user()->userModules->{$module}) {
            return response()->json(['error' => 'Module Unauthorized'], 401);
        }

        return $next($request);
    }
}
