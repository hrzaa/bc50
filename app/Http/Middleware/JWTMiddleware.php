<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, ...$roles)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $checkLevel = JWTAuth::user();
            if (!in_array($checkLevel->level, $roles)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak!',
                ], 401);
            }
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token Tidak Valid',
                ], 401);
            } else if ($e instanceof TokenExpiredException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token Expired',
                ], 401);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorization',
                ], 401);
            }
        }
        return $next($request);
    }
}
