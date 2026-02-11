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
    public function handle(Request $request, Closure $next,$type): Response
    {

        // If user is customer, restrict POST, PUT, PATCH, DELETE methods
        if ($request->user()?->type === 'customer' && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            return response()->json([
                'message' => 'Unauthorized. Customers can only perform GET requests.'
            ], 403);
        }

        // if ($request->user() && $request->user()->type !== 'admin') {
        //     abort(403, 'Unauthorized');
        // }
        return $next($request);
    }

}
