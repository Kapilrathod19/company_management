<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $module, $action)
    {
        $user = auth()->user();

        $perm = \App\Models\Permission::where([
            'user_id' => $user->id,
            'module'  => $module
        ])->first();

        if (!$perm || $perm->$action != 1) {
            abort(403, 'You do not have permission to access this page');
        }

        return $next($request);
    }
}
