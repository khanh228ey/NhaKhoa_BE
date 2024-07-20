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
        $refreshToken = $request->cookie('refresh_token');

        if ($refreshToken) {
            try {
                // Tạo một token chính mới từ token làm mới
                $newToken = JWTAuth::refresh($refreshToken);
                $user = JWTAuth::setToken($newToken)->toUser();

                // Tạo lại token làm mới và thiết lập lại cookie
                $newRefreshToken = JWTAuth::fromUser($user, ['exp' => Carbon::now()->addDays(30)->timestamp]);
                $cookie = Cookie::make('refresh_token', $newRefreshToken, 43200, '/'); // 43200 phút = 30 ngày

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
            // Xóa cookie nếu không có token làm mới
            $cookie = Cookie::forget('refresh_token');
            // return response()->json(['error' => 'Unauthorized'], 401)->withCookie($cookie);
            JsonResponse::handle(401,'Yêu cầu đăng nhập',null,401)->withCookie($cookie);
        }
    }
}
