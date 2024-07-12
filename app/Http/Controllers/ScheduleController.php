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
        // $validator = $this->ScheduleValidation->Schedule();
        // if ($validator->fails()) {
        //       return JsonResponse::handle(400,ConstantsMessage::Bad_Request,$validator->messages(),400);
        // }
        $history = $this->ScheduleRepository->addSchedule($request->all());
        if ($history == false) {
                return JsonResponse::error(401,ConstantsMessage::ERROR,401);
        }
        return JsonResponse::handle(200, ConstantsMessage::Add, $history, 200);
    }


    public function getSchedule(Request $request)
    {
        $doctor_id = $request->get('doctor_id');
        // $date = $request->get('date', Carbon::today()->toDateString()); // Use today's date if no date is provided
        $perPage = $request->get('limit', 5); 
        $page = $request->get('page'); 
    
        $query = Schedule::query()->select('date')->distinct()
                    ->orderBy('date', 'ASC');
    
        if ($doctor_id) {
            $query->where('doctor_id', $doctor_id);
        }
        $query->get();
        if (is_null($page)) {
            $distinctDates = $query->pluck('date')->toArray();
            $schedules = Schedule::with(['doctor', 'time'])
                        ->whereIn('date', $distinctDates)
                        ->orderBy('date', 'ASC')
                        ->orderBy('doctor_id', 'ASC')
                        ->orderBy('created_at', 'DESC')
                        ->get();
        } else {
            $distinctDates = $query->pluck('date')->toArray();
            $startIndex = ($page - 1) * $perPage;
            $paginatedDates = array_slice($distinctDates, $startIndex, $perPage);
            $schedules = Schedule::with(['doctor', 'time'])
                        ->whereIn('date', $paginatedDates)
                        ->orderBy('date', 'ASC')
                        ->orderBy('doctor_id', 'ASC')
                        ->orderBy('created_at', 'DESC')
                        ->get();
        }
        $result = [];
        foreach ($schedules as $schedule) {
            $key = $schedule->date . '_' . $schedule->doctor_id;
    
            if (!isset($result[$key])) {
                $result[$key] = [
                    'doctor' => [
                        'id' => $schedule->doctor_id,
                        'name' => $schedule->doctor->name,
                    ],
                    'date' => $schedule->date,
                    'times' => [],
                ];
            }
            $result[$key]['times'][] = $schedule->time->time;
        }
        $result = array_values($result);
    
        return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $result, 200);
    }
    
    
    

    
}
