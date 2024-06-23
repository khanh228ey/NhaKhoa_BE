<?php

namespace App\Http\Controllers;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Models\Schedule;
use App\Models\Schedule_time;
use App\Models\User;
use App\RequestValidations\AppointmentValidation;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    //

    protected $appointmentRepository;
    protected $appointmentValidation;
    public function __construct(AppointmentValidation $appointmentValidation, AppointmentController $appointmentRepository)
    {
        $this->appointmentValidation = $appointmentValidation;
        $this->appointmentRepository = $appointmentRepository; 
      
    }

    Public function getDoctor(){
        $doctor = User::where('role_id',1)->where('status',1)->get();
        $result = $doctor->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'educatipn' => $item->education,
                'avatar' => $item->avatar,
            ];
        });
        return JsonResponse::handle(200,ConstantsMessage::SUCCESS,$result,200);
    }

    Public function getDoctorDetail($id){
        $doctor = User::where('role_id',1)->where('status',1)->find($id);
        $result = 
             [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'education' => $doctor->education,
                'certificate' => $doctor->certificate,
                'avatar' => $doctor->avatar,
            ];
     
        return JsonResponse::handle(200,ConstantsMessage::SUCCESS,$result,200);
    }


    Public function getDoctorSchedule($id){
        $doctor = User::where('role_id',1)->find($id);
        $Schedule = Schedule::where('doctor_id', $id)
        ->select('date')->distinct()->orderBy('date', 'Asc')->get();    
        $result =  [
                 $Schedule,
            ];
    
        return JsonResponse::handle(200,ConstantsMessage::SUCCESS,$result,200);
    }

    Public function getDoctorTimeslotsByDate($id, $date)
{
    $schedule = Schedule::with('time')
        ->where('doctor_id', $id)
        ->where('date', $date)
        ->get();

    if ($schedule->isEmpty()) {
        return JsonResponse::handle(404, 'No timeslots found for this doctor on the given date', null, 404);
    }

    $timeslots = $schedule->map(function ($item) {
        return [
             $item->time->time,
        ];
    });

    return JsonResponse::handle(200, ConstantsMessage::SUCCESS,  $timeslots, 200);
}


    Public function createAppointment(Request $request){
        $validator = $this->appointmentValidation->Appointment();
        if ($validator->fails()) {
            return JsonResponse::error(400,$validator->messages(),400);
        }
        $appointment = $this->appointmentRepository->addAppointment($request->all());
        if ($appointment['success'] == true) {
            return JsonResponse::handle(201, ConstantsMessage::SUCCESS, $appointment['appointment'], 201);     
        }
        return JsonResponse::error(401,$appointment['message'],401);
    }

        Public function getTime(){
                $time = Schedule_time::all();
                $timeslots = $time->map(function ($item) {
                    return [
                         $item->time,
                    ];
                });
            
                return JsonResponse::handle(200, ConstantsMessage::SUCCESS,  $timeslots, 200);
        }

}
