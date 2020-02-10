<?php

namespace Modules\DevelDashboard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckDashboardPermissions
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
            return redirect()->route('dashboard.index');
        }

        // Check if the user has all the permissions required by the route
        // TODO:
        // if (!auth()->user()->hasPermissions('admin_dashboard.access')) {
        //     return redirect()->route('dashboard.index');
        // }

        return $next($request);
    }
}
