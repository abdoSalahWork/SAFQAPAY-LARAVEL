<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class superAdminAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $admin = auth()->guard('admin')->user();
        if ($admin->is_super_admin == 1 or $admin->admins == 1) {
            return $next($request);
        }

        return response()->json([
            'message' => 'Please Login As Super Admin'
        ], 404);
    }
}
