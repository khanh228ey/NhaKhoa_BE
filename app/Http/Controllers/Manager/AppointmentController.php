<?php

namespace App\Http\Controllers\Manager;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Events\AppointmentCreatedEvent;
use App\Events\NotificationEvent;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\DeleteResource;
use App\Models\Appointment;
use App\Repositories\Manager\AppointmentRepository;
use App\RequestValidations\AppointmentValidation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Manager\NotiRepository;

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

    Public function createAppointment(Request $request){
        $validator = $this->appointmentValidation->Appointment();
        if ($validator->fails()) {
            $firstError = $validator->messages()->first();
            return JsonResponse::handle(400, $firstError,$validator->messages(),400);
        }
        $appointment = $this->appointmentRepository->addAppointment($request->all());
        if ($appointment['success'] == true) {
            $appointment = new AppointmentResource($appointment['appointment']);
            return JsonResponse::handle(200, "Đặt lịch hẹn thành công", $appointment, 200);     
        }
        return JsonResponse::error(500,$appointment['message'],500);
    }

    public function getAppointment(Request $request)
    {
        $perPage = $request->get('limit', 10);
        $page = $request->get('page'); 
        $query = Appointment::with(['doctor','Services'])->orderBy('created_at','DESC');
        if (!is_null($page)) {
            $data = $query->paginate($perPage, ['*'], 'page', $page);
            $appointment = $data->items();
        } else {
            $appointment = $query->get();
        }
        $result = AppointmentResource::collection($appointment);
        return JsonResponse::handle(200, ConstantsMessage::SUCCESS,  $result, 200);
    }
    
    Public function findById($id){
        try {
            $appointment = Appointment::with(['Doctor','Services'])->findOrFail($id); 
            $result = new AppointmentResource($appointment);
            return JsonResponse::handle(200, ConstantsMessage::SUCCESS,  $result, 200);
        } catch (ModelNotFoundException $e) {
            return JsonResponse::handle(404, "Lịch đặt hẹn không tồn tại", null, 404);
        }
    }

    Public function updateAppointment(Request $request,$id){
        try{
                $appointment = Appointment::findOrFail($id);
                $validator = $this->appointmentValidation->Appointment();
                if ($validator->fails()) {
                    $firstError = $validator->messages()->first();
                    return JsonResponse::handle(400, $firstError,$validator->messages(),400);
                }
                $appointment = $this->appointmentRepository->updateAppointment($request->all(),$appointment);
                if ($appointment['success'] == true) {
                    return JsonResponse::handle(200, ConstantsMessage::Update, $appointment['appointment'], 200);     
                }
                return JsonResponse::error(401,$appointment['message'],401);
            }
            catch (ModelNotFoundException $e) {
                return JsonResponse::handle(404,"Lịch đặt hẹn không tồn tại", null, 404);
            }
            catch (\Exception $e) {
                return JsonResponse::error(500, ConstantsMessage::ERROR, 500);
            }
    }

    Public function deleteAppointment($id){
        try {
            $appointment = Appointment::findOrFail($id);
            $appointment->services()->detach();
            $appointment->delete();
            $appointment = new DeleteResource($appointment);
            return JsonResponse::handle(200, ConstantsMessage::Delete,$appointment, 200);
        } catch (ModelNotFoundException $e) {
            return JsonResponse::handle(404, "Lịch đặt hẹn không tồn tại", null, 404);
        } catch (\Exception $e) {
            return JsonResponse::error(500, ConstantsMessage::ERROR, 500);
        }
    }
    
}

