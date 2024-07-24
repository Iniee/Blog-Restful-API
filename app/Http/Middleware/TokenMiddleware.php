<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponses;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenMiddleware
{
    use ApiResponses;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Retrieve the token from the request header
        $token = $request->header('Authorization');

        // Check if the token matches the expected value
        if ($token !== 'vg@123') {
            return $this->unauthorizedApiResponse("Unauthorized");
        }

        return $next($request);
    }
}