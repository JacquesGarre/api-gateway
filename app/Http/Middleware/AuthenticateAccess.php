<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use App\Traits\ApiResponse;

class AuthenticateAccess
{
    use ApiResponse;
    
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $next($request);
        $allowedSecrets = explode(',', env('ALLOWED_SECRETS'));
        if (in_array($request->header('Authorization'), $allowedSecrets)) {
            return $next($request);
        }

        abort(Response::HTTP_UNAUTHORIZED, 'Unauthorized access');
    }
}
