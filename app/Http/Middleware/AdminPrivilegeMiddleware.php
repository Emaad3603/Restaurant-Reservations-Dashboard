<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminPrivilegeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $privilege
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $privilege)
    {
        $user = auth('admin')->user();
        // Allow super admin to access everything
        if ($user && $user->admin) {
            return $next($request);
        }
        if (!$user || !$user->privilege || !$user->privilege->$privilege) {
            abort(403, 'Unauthorized.');
        }
        return $next($request);
    }
} 