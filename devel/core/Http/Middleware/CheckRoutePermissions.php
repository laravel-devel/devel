<?php

namespace Devel\Core\Http\Middleware;

use Closure;
use Devel\Modules\Facades\Module;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CheckRoutePermissions
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
        $permissions = $request->route()->getAction()['permissions'] ?? [];

        if (!is_array($permissions) && !is_string($permissions)) {
            throw new \Exception('The route permissions should be set in as a string or an array of strings.');
        }

        // If the route has no permissions -> continue
        if (!$permissions) {
            return $next($request);
        }

        // Guests can't have any permissions
        if (!auth()->check()) {
            return redirect('/')
                ->with('error', 'You don\'t have permission to perform this action.');
        }

        // Check if the user has all the permissions required by the route
        if (!auth()->user()->hasPermissions($permissions)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'message' => 'You don\'t have permission to perform this action.',
                ], 401);
            }

            if (back()->getTargetUrl() === url()->current()) {
                $dm = Module::find('develdashboard')->isINstalled();

                if (Str::startsWith(url()->current(), route('dashboard.index')) && auth()->user()->hasPermissions('admin_dashboard.access') && $dm && $dm->isInstalled() && $dm->isEnabled()) {
                    // Admin dashboard
                    return redirect()->route('dashboard.index')
                        ->with('error', 'You don\'t have permission to perform this action.');
                } else {
                    // Public site
                    return redirect('/')
                        ->with('error', 'You don\'t have permission to perform this action.');
                }
            }

            return back()->with('error', 'You don\'t have permission to perform this action.');
        }

        return $next($request);
    }
}
