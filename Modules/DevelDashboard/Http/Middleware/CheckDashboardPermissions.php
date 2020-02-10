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

        $permissions = $request->route()->action['permissions'] ?? [];

        if (!is_array($permissions) && !is_string($permissions)) {
            throw new \Exception('The route permissions should be set in as a string or an array of strings.');
        }

        if (is_string($permissions)) {
            $permissions = [$permissions];
        }

        // Check if the user has all the permissions required by the route
        foreach ($permissions as $permission) {
            if (!auth()->user()->hasPermissions($permission)) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'message' => 'You don\'t have permission to perform this action',
                    ], 401);
                }

                return redirect()->route('dashboard.index');
            }
        }

        return $next($request);
    }
}
