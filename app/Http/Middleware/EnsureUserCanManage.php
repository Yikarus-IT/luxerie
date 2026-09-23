<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserCanManage
{
    public function handle(Request $request, Closure $next, string $area): Response
    {
        $user = $request->user();
        abort_unless($user && ($request->isMethodSafe() || $user->is_admin || $user->canManage($area)), 403);

        return $next($request);
    }
}
