<?php

namespace App\Http\Controllers;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Models\Schedule;
use App\Repositories\ScheduleRepository;
use App\RequestValidations\ScheduleValidation;
use Illuminate\Http\Request;
use Carbon\Carbon;
class ScheduleController extends Controller
{
    //
    protected $ScheduleRepository;
    protected $ScheduleValidation;
    public function __construct(ScheduleValidation $ScheduleValidation, ScheduleRepository $ScheduleRepository)
    {
        $this->ScheduleValidation = $ScheduleValidation;
        $this->ScheduleRepository = $ScheduleRepository; 
      
    }
    Public function createSchedule(Request $request){
        $history = $this->ScheduleRepository->addSchedule($request->all());
        if ($history == false) {
                return JsonResponse::error(401,ConstantsMessage::ERROR,401);
        }
        return JsonResponse::handle(200, ConstantsMessage::Add, $history, 200);
    }

    public function getSchedule(Request $request)
{
    $dateInput = $request->get('date');
    $date = $dateInput ? Carbon::parse($dateInput)->setTimezone('Asia/Ho_Chi_Minh') : Carbon::now('Asia/Ho_Chi_Minh');
    $startDate = $date->startOfWeek(Carbon::MONDAY);
    $endDate = $startDate->copy()->addDays(5);
    
    $query = Schedule::query()->with(['doctor', 'time'])
                ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->orderBy('date', 'ASC')
                ->orderBy('doctor_id', 'ASC');
    $schedules = $query->get();
    $result = [];
    $weekDays = [
        Carbon::MONDAY => 'monday',
        Carbon::TUESDAY => 'tuesday',
        Carbon::WEDNESDAY => 'wednesday',
        Carbon::THURSDAY => 'thursday',
        Carbon::FRIDAY => 'friday',
        Carbon::SATURDAY => 'saturday',
    ];

    foreach ($schedules as $schedule) {
        $dateKey = $schedule->date;
        $doctorKey = $schedule->doctor_id;
        $dayOfWeek = Carbon::parse($schedule->date)->dayOfWeek;

        if (!isset($result[$dateKey])) {
            $result[$dateKey] = [
                'today' => $weekDays[$dayOfWeek],
                'date' => $schedule->date,
                'doctor' => []
            ];
        }
        if (!isset($result[$dateKey]['doctor'][$doctorKey])) {
            $result[$dateKey]['doctor'][$doctorKey] = [
                'id' => $schedule->doctor_id,
                'name' => $schedule->doctor->name,
                'times' => []
            ];
        }
        if (!in_array($schedule->time->time, $result[$dateKey]['doctor'][$doctorKey]['times'])) {
            $result[$dateKey]['doctor'][$doctorKey]['times'][] = $schedule->time->time;
        }
    }

    foreach ($result as &$daySchedule) {
        foreach ($daySchedule['doctor'] as &$doctorchedule) {
            sort($doctorchedule['times']);
            $doctorchedule['times'] = $this->mergeSameDayTimes($doctorchedule['times']);
            
            // Thêm key 'time' cho mỗi thời gian
            $doctorchedule['times'] = array_map(function ($time) {
                return ['time' => $time];
            }, $doctorchedule['times']);
        }
        $daySchedule['doctor'] = array_values($daySchedule['doctor']);
    }

    $result = array_values($result);

    return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $result, 200);
}

    
    private function mergeSameDayTimes($times)
    {
        if (empty($times)) {
            return [];
        }
    
        $mergedTimes = [];
        $currentRange = $times[0];
    
        foreach ($times as $time) {
            if ($currentRange != $time) {
                list($currentStart, $currentEnd) = explode(' - ', $currentRange);
                list($nextStart, $nextEnd) = explode(' - ', $time);
                if ($currentEnd == $nextStart) {
                    $currentRange = $currentStart . ' - ' . $nextEnd;
                } else {
                    $mergedTimes[] = $currentRange;
                    $currentRange = $time;
                }
            }
        }

        $mergedTimes[] = $currentRange;
    
        return $mergedTimes;
    }
    

    Public function updateSchedule(){
        
    }
    
    
}
