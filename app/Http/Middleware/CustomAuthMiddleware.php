<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class CustomAuthMiddleware
{
    protected $authRoutes = [
        '/register',
        '/login',
        '/profile',
        '/logout',
        '/store',
        '/show/{id}',
        '/update/{id}',
        '/destroy/{id}',
        '/search/{id}',
        '/index',
    ];

    public function handle($request, Closure $next)
    {
        $route = $request->path();
        
        // Check if the requested route matches any of the routes in the $authRoutes array
        if (in_array($route, $this->authRoutes)) {
            return $next($request);
        }

        // If not authorized, return a "Not Authorized" response as JSON
        return response()->json(['message' => 'Not Authorized'], Response::HTTP_UNAUTHORIZED);
    }
}
