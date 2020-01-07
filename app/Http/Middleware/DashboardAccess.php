<?php

namespace App\Http\Middleware;

use Closure;

class DashboardAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('dashboard.auth.login');
        }

        // TODO: Check permissions, I can access the route instance and its
        // properties here
        return $next($request);
    }
}
