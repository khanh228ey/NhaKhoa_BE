<?php

namespace App\Http\Middleware;

use App\Commons\Responses\JsonResponse;
use App\Models\Appointment;
use Closure;
use Illuminate\Http\Request;

class PassesAppointment
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
        $data = $request->all();
        $check = Appointment::where('name',$data['name'])->where('phone',$data['phone'])
            ->where('date',$data['date'])->where('time',$data['time'])->get();
        if($check->isEmpty()){
            return $next($request);
        }
        return JsonResponse::handle(409,"Lịch hẹn đã được đặt",null,409);
        
    }
}
