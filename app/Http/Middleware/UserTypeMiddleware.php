<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            return response()->json(['message' => 'Unauthenticated!'], 401);
        }

        if ($request->route()) {
            if ($request->route('id')) return $next($request);
            $routeName = $request->route()->getName();
            if (Auth::user()->type == 'customer' &&  $routeName == 'book_requests.update' || $routeName == 'book_requests.show' || $routeName == 'book_requests.destroy')
                return $next($request);
        }



        if (Auth::user()->type == 'customer' &&  ($request->customer_id ?? $request->route('customer')?->id)  !=  Auth::user()->customer->id)
            return response()->json(['message' => 'Unauthenticated!!!!'], 401);

        // Check if user type is allowed for this route
        if (!in_array($request->user()->type, $types)) {
            return response()->json([
                'message' => 'Access denied. Insufficient permissions.'
            ], 403);
        }

        return $next($request);
    }
}
