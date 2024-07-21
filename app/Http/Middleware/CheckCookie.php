<?php

namespace App\Http\Middleware;

use App\Commons\Responses\JsonResponse;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckCookie
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
            $response = $next($request);
            $refreshToken = $request->cookie('refresh_token');
            if ($refreshToken) {
                // Reset thời gian cho cookie refresh token
                $cookie = Cookie::make('refresh_token', $refreshToken, 48, '/'); // 43200 phút = 30 ngày
            } else {
                // Tạo mới refresh token nếu chưa có
                $user = JWTAuth::parseToken()->authenticate();
                $newRefreshToken = JWTAuth::fromUser($user, ['exp' => Carbon::now()->addDays(2)->timestamp]);
                $cookie = Cookie::make('refresh_token', $newRefreshToken, 48, '/');
            }
            return $response->withCookie($cookie);
    
        } catch (TokenExpiredException $e) {
            // Token chính hết hạn, kiểm tra refresh token
            $refreshToken = $request->cookie('refresh_token');
            if ($refreshToken) {
                try {
                    // Tạo một token chính mới từ refresh token
                    $newToken = JWTAuth::refresh($refreshToken);
                    $user = JWTAuth::setToken($newToken)->toUser();
                    // Tạo lại refresh token và thiết lập lại cookie
                    $newRefreshToken = JWTAuth::fromUser($user, ['exp' => Carbon::now()->addDays(2)->timestamp]);
                    $cookie = Cookie::make('refresh_token', $newRefreshToken, 48, '/'); // 43200 phút = 30 ngày
                    // Gán token mới vào yêu cầu
                    $request->headers->set('Authorization', 'Bearer ' . $newToken);
                    // Thêm cookie vào phản hồi
                    $response = $next($request);
                    return $response->withCookie($cookie);
                } catch (TokenExpiredException $e) {
                    // Token làm mới hết hạn
                    return response()->json(['error' => 'Unauthorized'], 401);
                }
            } else {
                // Xóa cookie nếu không có refresh token
                $cookie = Cookie::forget('refresh_token');
                return JsonResponse::handle(401, 'Yêu cầu đăng nhập', null, 401)->withCookie($cookie);
            }
        } catch (\Exception $e) {
            // Token không hợp lệ hoặc không được cung cấp
            $cookie = Cookie::forget('refresh_token');
            return JsonResponse::handle(401, 'Unauthorized', null, 401)->withCookie($cookie);
        }
    }
}
