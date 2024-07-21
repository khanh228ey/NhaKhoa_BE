<?php

namespace App\Http\Middleware;

use App\Commons\Responses\JsonResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next,$role)
    {
        if (Auth::check() && Auth::user()->role_id == $role) {
            return $next($request);
        }

        // return response()->json(['error' => 'Unauthorized'], 403);
        return JsonResponse::handle(401,'Bạn k có quyền truy cập',null,401);
    }
    
}
