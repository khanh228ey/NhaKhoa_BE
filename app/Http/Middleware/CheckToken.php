<?php

namespace App\Http\Middleware;

use App\Commons\Responses\JsonResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckToken
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
            if(Auth::user()->status ==0 ){
                $cookie = Cookie::forget('refresh_token');
                return JsonResponse::handle(401,'Tài khoản bạn đã bị khóa',null,401)->withCookie($cookie);
            }
        } catch (\Exception $e) {
            return JsonResponse::handle(401,'Phiên đăng nhập hết hạn',null,401);
        }
        return $next($request);
    }
}
