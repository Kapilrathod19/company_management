<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['email' => 'You must log in first.']);
        }

        if (Auth::user()->role !== $role) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'Access denied.']);
        }

        return $next($request);
    }
}
