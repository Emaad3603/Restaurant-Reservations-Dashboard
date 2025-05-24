<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DebugSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $sessionId = $request->session()->getId();
        
        // Log the session ID for debugging
        Log::info('Session ID: ' . $sessionId);
        
        // Check if the session exists in the database
        $session = DB::table('sessions')
            ->where('session_id', $sessionId)
            ->first();
        
        if ($session) {
            Log::info('Session found in database');
        } else {
            Log::warning('Session not found in database');
        }
        
        return $next($request);
    }
} 