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

    Public function updateSchedule(Request $request){
        $history = $this->ScheduleRepository->updateSchedule($request->all());
        if ($history == false) {
                return JsonResponse::error(401,ConstantsMessage::ERROR,401);
        }
        return JsonResponse::handle(200, ConstantsMessage::Update, $history, 200);
    }


    
    public function getSchedule(Request $request){
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
            Carbon::MONDAY => '2',
            Carbon::TUESDAY => '3',
            Carbon::WEDNESDAY => '4',
            Carbon::THURSDAY => '5',
            Carbon::FRIDAY => '6',
            Carbon::SATURDAY => '7',
        ];
        foreach ($schedules as $schedule) {
            $dateKey = $schedule->date;
            $doctorKey = $schedule->doctor_id;
            $dayOfWeek = Carbon::parse($schedule->date)->dayOfWeek;

            if (!isset($result[$dateKey])) {
                $result[$dateKey] = [
                    'key' => $weekDays[$dayOfWeek],
                    'date' => $schedule->date,
                    'doctor' => []
                ];
            }
            if (!isset($result[$dateKey]['doctor'][$doctorKey])) {
                $result[$dateKey]['doctor'][$doctorKey] = [
                    'id' => $schedule->doctor->id,
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
            $doctorchedule['times'] = array_map(function ($time) {
                return ['time' => $time];
            }, $doctorchedule['times']);
        }
        $daySchedule['doctor'] = array_values($daySchedule['doctor']);
    }

    $result = array_values($result);

    return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $result, 200);
}

    private function mergeSameDayTimes($times){
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
    
    public function getScheduleDetails(Request $request) {
        $dateInput = $request->get('date');
        $doctorId = $request->get('doctor_id');
        if (!$dateInput || !$doctorId) {
            return JsonResponse::handle(400, "Không tìm tháy", null, 400);
        }
    
        $schedules = Schedule::query()
                    ->with(['doctor', 'time'])
                    ->where('date', $dateInput)
                    ->where('doctor_id', $doctorId)
                    ->orderBy('time_id', 'ASC')
                    ->get();
        if ($schedules->isEmpty()) {
            return JsonResponse::handle(404,'Không tìm thấy lịch làm việc',null,404);
        }
        $doctor = $schedules->first()->doctor;
        $uniqueTimes = [];
        $times = [];
        foreach ($schedules as $schedule) {
            $timeId = $schedule->time_id;
            $time = $schedule->time->time;
            if (!in_array($time, $uniqueTimes)) {
                $uniqueTimes[] = $time;
                $times[] = [
                    'id' => $timeId,
                    'time' => $time
                ];
            }
        }
        $result = [
            'date' => $dateInput,
            'doctor' => [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'times' => $times
            ]
        ];
        return JsonResponse::handle(200,ConstantsMessage::SUCCESS,$result,200);
    }
    
    Public function deleteSchedule(Request $request){
        $data = $request->all();
        $schedules = Schedule::where('date',$data['date'])->where('doctor_id',$data['doctor_id'])->get();
        if($schedules ){
            $schedule = $this->ScheduleRepository->deleteSchedule($schedules);
            if($schedule ==true){
                return JsonResponse::handle(200,ConstantsMessage::Delete,null,200);
            }
        }else{
            return JsonResponse::handle(404,"Không tìm thấy lịch làm việc",null,404);
        }
       
        
    }
    
    
}
