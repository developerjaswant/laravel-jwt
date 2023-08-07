<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class LoginMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the request URL matches the login route
        if ($request->is('/login')) {
            // Generate the token here
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string'
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
          
            if (!$token = auth()->attempt($validator->validated())) {
                return response()->json([
                    'message' => 'Invalid credentials',
                ], 401);
            }
    
            return $this->createNewToken($token);
        } else {
            return response()->json([
                'message' => 'Not found.',
            ], 404);
        }

        return $next($request);
    }
}
