<?php

namespace App\Http\Controllers;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Models\Schedule;
use App\Repositories\ScheduleRepository;
use App\RequestValidations\ScheduleValidation;
use Illuminate\Http\Request;

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
        //     return JsonResponse::error(400,$validator->messages(),400);
        // }
        $history = $this->ScheduleRepository->addSchedule($request->all());
        if ($history == false) {
                return JsonResponse::error(401,ConstantsMessage::ERROR,401);
        }
        return JsonResponse::handle(201, ConstantsMessage::SUCCESS, $history, 201);
    }

    public function getSchedule(Request $request)
    {
        $perPage = $request->get('limit', 10);
        $page = $request->get('page'); 
        $doctor_id = $request->get('doctor_id');
        $date = $request->get('date');
        $query = Schedule::with(['doctor','time'])->orderBy('created_at','DESC');
        if ($doctor_id) {
            $query->where('doctor_id', $doctor_id);
        }
        if ($date) {
            $query->where('date', 'LIKE', "%{$date}%");
        }
        if (!is_null($page)) {
            $data = $query->paginate($perPage, ['*'], 'page', $page);
            $schedule = $data->items();
        } else {
            $schedule = $query->get();
        }
        return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $schedule, 200);
    }

    Public function deleteAppointment($id){
        try {
            $schedule = Schedule::findOrFail($id);
            $schedule->delete();
            return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $schedule, 200);
        } catch (\Exception $e) {
            return JsonResponse::error(500, ConstantsMessage::ERROR, 500);
        }
    }
}