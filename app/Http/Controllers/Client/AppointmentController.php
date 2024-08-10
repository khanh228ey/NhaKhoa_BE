<?php

namespace App\Http\Controllers\Client;

use App\Commons\Responses\JsonResponse;
use App\Events\AppointmentCreatedEvent;
use App\Events\NotificationEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Repositories\Manager\AppointmentRepository;
use App\Repositories\Manager\NotiRepository;
use App\RequestValidations\AppointmentValidation;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    //
    protected $appointmentRepository;
    protected $appointmentValidation;
    public function __construct(AppointmentValidation $appointmentValidation, AppointmentRepository $appointmentRepository)
    {
        $this->appointmentValidation = $appointmentValidation;
        $this->appointmentRepository = $appointmentRepository; 
    }
    Public function createAppointment(Request $request,$lang){
        $messsage = $lang=='vi' ?'Đặt lịch thành công':'Scheduled successfully';
        $validator = $this->appointmentValidation->Appointment();
        if ($validator->fails()) {
            $firstError = $validator->messages()->first();
            return JsonResponse::handle(400, $firstError,$validator->messages(),400);
        }
        $appointment = $this->appointmentRepository->addAppointment($request->all());
        if ($appointment['success'] == true) {
            //tạo thông báo
            $appointment = new AppointmentResource($appointment['appointment']);
            $notiAppointment = new NotiRepository();
            $noti = $notiAppointment->createNotiAppointment($appointment); 
            event(new NotificationEvent($noti));
            return JsonResponse::handle(200, $messsage, $appointment, 200);     
        }
        return JsonResponse::error(500,$appointment['message'],500);
    }
}
