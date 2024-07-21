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
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $roles = explode(',', $roles[0]);
        
        if (Auth::check() && in_array(Auth::user()->role_id, $roles)) {
            return $next($request);
        }
        
        return JsonResponse::handle(403, 'Bạn không có quyền truy cập', null, 403);
    }
    
}
