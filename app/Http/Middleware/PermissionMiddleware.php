<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, $permission, $guard = null): Response
    {
        $authGuard = app('auth')->guard($guard);

        if ($authGuard->guest()) {
            return redirect()->route('login');
        }

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        foreach ($permissions as $permission) {
            if ($authGuard->user()->can($permission)) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized action.');
    }
}