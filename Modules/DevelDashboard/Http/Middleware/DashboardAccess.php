<?php

namespace Modules\DevelDashboard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DashboardAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('dashboard.auth.login');
        }

        // Check if the user has permission to access the admin dashboard
        if (!auth()->user()->hasPermissions('admin_dashboard.access')) {
            return redirect('/');
        }

        return $next($request);
    }
}
