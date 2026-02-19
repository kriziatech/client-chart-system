<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // If user doesn't belong to a company, we might want to logout or show error
            // For now, if they are Super Admin, they might not need a company
            if (!$user->company_id && !$user->isSuperAdmin()) {
            // Option 1: Logout
            // Auth::logout();
            // return redirect()->route('login')->with('error', 'No company assigned to your account.');

            // Option 2: Let them proceed but they won't see any data due to global scope
            // pass
            }
        }

        return $next($request);
    }
}