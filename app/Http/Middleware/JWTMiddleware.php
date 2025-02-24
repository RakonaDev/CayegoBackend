<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class JWTMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
   * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
   */
  public function handle(Request $request, Closure $next)
  {
    try {
      JWTAuth::parseToken()->authenticate();
    } catch (TokenExpiredException $e) {
      return response()->json(['message' => 'Token expirado'], 401);
    } catch (TokenInvalidException $e) {
      return response()->json(['message' => 'Token inválido'], 401);
    } catch (JWTException $e) {
      return response()->json(['message' => 'Authorization token not found'], 401);
    }
    return $next($request);
  }
}
