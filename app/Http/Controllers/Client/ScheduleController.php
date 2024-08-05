<?php

namespace App\Http\Controllers\Client;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Schedule_time;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    //
    public function getDoctorTimeslotsByDate($lang,$id, $date)
    {
        $message = $lang=='vi' ?'Không có thời gian trong ngày':'There is no time of day';
        $schedule = Schedule::with('time')
            ->where('doctor_id', $id)
            ->where('date', $date)
            ->where('status', 1)
            ->get();
    
        if ($schedule->isEmpty()) {
            return JsonResponse::handle(404, $message, null, 404);
        }
    
        $timeslots = $schedule->map(function ($item) {
            return ['time' => $item->time->time];
        })->unique('time')->values()->toArray();
    
        return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $timeslots, 200);
    }
    
    
    public function getDoctorScheduleWithTimeslots($lang,$id) {
        try {
            $message = $lang=='vi' ?'Không tìm thấy lịch làm việc của bác sĩ':'Doctors schedule could not be found';
            $doctor = User::where('role_id', 3)->findOrFail($id);
            $schedules = Schedule::with('time')
                ->where('doctor_id', $id)
                ->where('status', 1)
                ->where('date', '>=', now()->toDateString()) 
                ->orderBy('date', 'Asc')
                ->get();
    
            if ($schedules->isEmpty()) {
                return JsonResponse::handle(404, $message, null, 404);
            }
            $datesProcessed = [];
            foreach ($schedules as $schedule) {
                $date = $schedule->date;
    
                if (!isset($datesProcessed[$date])) {
                    $datesProcessed[$date] = [
                        'date' => $date,
                    ];
                }
    
            }
            $limitedScheduleData = array_slice(array_values($datesProcessed), 0, 7);
            return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $limitedScheduleData, 200);
        } catch (ModelNotFoundException $e) {
            return JsonResponse::handle(404, ConstantsMessage::Not_Found, null, 404);
        }
    }

    public function getTime()
    {
        $time = Schedule_time::all();
        $timeslots = $time->map(function ($item) {
            return ['id' => (int)$item->id ,
                    'time' => $item->time];
        })->toArray();
        
        return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $timeslots, 200);
    }
}
