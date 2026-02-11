<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserTypeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // Better approach - define clear permission rules
public function handle(Request $request, Closure $next, ...$types): Response
{
    if (!$request->user()) {
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }

    // Check if user type is allowed for this route
    if (!in_array($request->user()->type, $types)) {
        return response()->json([
            'message' => 'Access denied. Insufficient permissions.'
        ], 403);
    }

    return $next($request);
}


}
