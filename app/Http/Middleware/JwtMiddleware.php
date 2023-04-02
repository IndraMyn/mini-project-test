<?php
namespace App\Http\Middleware;


use Closure;
use Exception;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Http\Middleware\BaseMiddleware;


class JwtMiddleware extends BaseMiddleware
{


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            return response()->json(['message' => 'Token is Invalid'], 403);
        }
        return $next($request);
    }
}