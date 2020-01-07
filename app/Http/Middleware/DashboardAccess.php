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

        // Check if the user has permission to access the admin dashboard
        if (!auth()->user()->hasPermissions('admin_dashboard.access')) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
