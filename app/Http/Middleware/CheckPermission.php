<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next ,$permission = null)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Bạn cần đăng nhập để truy cập'], 401);
        }

        $user = Auth::user();

        if ($user->can($permission)) {
            return $next($request);
        }

        return response()->json(['error' => 'Bạn không có quyền truy cập'], 403);
    }
}

