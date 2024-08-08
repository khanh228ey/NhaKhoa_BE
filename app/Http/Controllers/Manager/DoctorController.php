<?php

namespace App\Http\Controllers\Manager;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Translate\DoctorResource;
use App\Models\Schedule;
use App\Models\Schedule_time;
use App\Models\User;
use App\Repositories\Client\DoctorRepository;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    //
    protected $doctorRepo;
    public function __construct(DoctorRepository $doctor)
    {
        $this->doctorRepo = $doctor; 
    }

    public function jsonDoctor($doctor){
        $data = $doctor->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'avatar' => $item->avatar,
                'phone_number' => $item->phone_number,
                'description' => $item->description,
            ];
        });
        return $data;
    }
    public function jsonDoctorDetail($doctor){
        $result = 
        [
            'id' => $doctor->id,
            'name' => $doctor->name,
            'description' => $doctor->description,
            'avatar' => $doctor->avatar,
            'birthday' => $doctor->birthday,
            'email' => $doctor->email,
            'gender' => (int)$doctor->gender,
        ];
        return $result;
    }
    Public function getDoctor(Request $request){
        $date = $request->get('date');
        $time = $request->get('time');
        if(isset($date) && isset($time)){
            $doctor = Schedule::where('date', $date)->with(['time' => function($query) use ($time){
                $query->where('time',$time);
            }])->get();
            $result = $doctor->map(function ($item) {
                return [
                    'id' => $item->Doctor->id,
                    'name' => $item->Doctor->name,
                    'avatar' => $item->Doctor->avatar,
                    'phone_number' => $item->Doctor->phone_number,
                ];
            })->unique('id')->values()->toArray();
            return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $result, 200);
        }else{
            $doctor = User::where('role_id',3)->where('status',1)->get();
            $result =  $this->jsonDoctor($doctor);
        }
        return JsonResponse::handle(200,ConstantsMessage::SUCCESS,$result,200);
    }

    Public function getDoctorDetail($id){
        try {
            $doctor = $this->doctorRepo->findById($id);
            if($doctor == false){
                return JsonResponse::handle(404, ConstantsMessage::Not_Found, null, 404);
            }
            $result =  $this->jsonDoctorDetail($doctor);
            return JsonResponse::handle(200, ConstantsMessage::SUCCESS,  $result, 200);
        } catch (Exception $e) {
            return JsonResponse::handle(500, ConstantsMessage::ERROR, null, 500);
        }
        
    }

    public function getDoctorTimeslotsByDate($id, $date)
    {
        $message = 'Không có thời gian trong ngày';
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
    
    
    public function getDoctorScheduleWithTimeslots($id) {
        try {
            $message = 'Không tìm thấy lịch làm việc của bác sĩ';
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
