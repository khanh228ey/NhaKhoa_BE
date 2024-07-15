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
                    'doctors' => []
                ];
            }
    
            if (!isset($result[$dateKey]['doctors'][$doctorKey])) {
                $result[$dateKey]['doctors'][$doctorKey] = [
                    'id' => $schedule->doctor_id,
                    'name' => $schedule->doctor->name,
                    'times' => []
                ];
            }
            if (!in_array($schedule->time->time, $result[$dateKey]['doctors'][$doctorKey]['times'])) {
                $result[$dateKey]['doctors'][$doctorKey]['times'][] = $schedule->time->time;
            }
        }
    
        foreach ($result as &$daySchedule) {
            foreach ($daySchedule['doctors'] as &$doctorSchedule) {
                sort($doctorSchedule['times']);
                $doctorSchedule['times'] = $this->mergeSameDayTimes($doctorSchedule['times']);
            }
            $daySchedule['doctors'] = array_values($daySchedule['doctors']);
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
    
                // Chỉ nối khi khung giờ liền nhau
                if ($currentEnd == $nextStart) {
                    $currentRange = $currentStart . ' - ' . $nextEnd;
                } else {
                    // Nếu không nối, lưu khung giờ hiện tại và chuyển sang khung giờ tiếp theo
                    $mergedTimes[] = $currentRange;
                    $currentRange = $time;
                }
            }
        }
    
        // Thêm khung giờ cuối cùng vào kết quả
        $mergedTimes[] = $currentRange;
    
        return $mergedTimes;
    }
    

    Public function updateSchedule(){

    }
    
    
}
