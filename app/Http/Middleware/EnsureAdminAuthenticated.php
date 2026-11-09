<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAdminAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * Allows access to:
     * 1. Users authenticated via the 'admin' guard (App\Models\Admin).
     * 2. Users authenticated via the 'web' guard who have administrative privileges
     *    (status == 2, 'Admin' role, or 'admin.dashboard' permission).
     *
     * Blocks and redirects unauthenticated visitors and regular frontend users to admin login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Check if authenticated through the 'admin' guard
        if (Auth::guard('admin')->check()) {
            $admin = Auth::guard('admin')->user();
            if ($admin && (int) $admin->status === 1) {
                return $next($request);
            }
        }

        // 2. Check if authenticated through the 'web' guard with full administrative credentials
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();

            if (
                $user &&
                (int) $user->status === 2 &&
                (int) $user->active_status === 1 &&
                $user->can('admin.dashboard')
            ) {
                return $next($request);
            }

            // Authenticated ordinary frontend user (customer) or unauthorized web user
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized action.'], 403);
            }

            return redirect()->route('admin.login');
        }

        // 3. Unauthenticated guest
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return redirect()->route('admin.login');
    }
}
