<?php

namespace App\Http\Middleware;

use App\Commons\Responses\JsonResponse;
use Closure;
use Illuminate\Http\Request;
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
            // Kiểm tra xem token chính có hợp lệ không
            JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            // Token chính hết hạn, tiếp tục đến middleware tiếp theo để kiểm tra token làm mới
            return $next($request);
        } catch (\Exception $e) {
            // Token không hợp lệ hoặc không được cung cấp
            $cookie = Cookie::forget('refresh_token');
            // return response()->json(['error' => 'Unauthorized'], 401)->withCookie($cookie);
            JsonResponse::handle(401,'Unauthorized',null,401)->withCookie($cookie);
        }
        return $next($request);
    }
}
