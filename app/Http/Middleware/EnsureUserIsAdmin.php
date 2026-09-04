<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user()?->is_admin || in_array($request->user()?->role, ['administrator', 'content_editor', 'product_manager', 'read_only'], true), 403);

        return $next($request);
    }
}
