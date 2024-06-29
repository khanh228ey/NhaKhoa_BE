<?php

namespace App\Http\Controllers;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Repositories\AppointmentRepository;
use App\RequestValidations\AppointmentValidation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

    Public function createAppointment(Request $request){
        $validator = $this->appointmentValidation->Appointment();
        if ($validator->fails()) {
            return JsonResponse::handle(400,ConstantsMessage::Bad_Request,$validator->messages(),400);
        }
        $appointment = $this->appointmentRepository->addAppointment($request->all());
        if ($appointment['success'] == true) {
            return JsonResponse::handle(201, ConstantsMessage::Add, $appointment['appointment'], 201);     
        }
        return JsonResponse::error(401,$appointment['message'],401);
    }

    public function getAppointment(Request $request)
    {
        $perPage = $request->get('limit', 10);
        $page = $request->get('page'); 
        $doctor_id = $request->get('doctor_id');
        $name = $request->get('name');
        $phone = $request->get('phone');
        $query = Appointment::with(['doctor','Services'])->orderBy('created_at','DESC');
        if ($doctor_id) {
            $query->where('doctor_id', $doctor_id);
        }
        if ($name) {
            $query->where('name', 'LIKE', "%{$name}%");
        }
        if ($phone) {
            $query->where('phone', 'LIKE', "%{$phone}%");
        }
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
                    return JsonResponse::handle(400,ConstantsMessage::Bad_Request,$validator->messages(),400);
                }
                $history = $this->appointmentRepository->update($request->all(),$appointment);
                return JsonResponse::handle(201, ConstantsMessage::Update, $history, 201);
            }
            catch (ModelNotFoundException $e) {
                return JsonResponse::handle(404,"Lịch đặt hẹn không tồn tại", null, 404);
            }catch (\Exception $e) {
                return JsonResponse::error(500, ConstantsMessage::ERROR, 500);
            }
          
    }

    Public function deleteAppointment($id){
        try {
            $appointment = Appointment::findOrFail($id);
            $appointment->services()->detach();
            $appointment->delete();
            return JsonResponse::handle(200, ConstantsMessage::Delete, null, 200);
        } catch (ModelNotFoundException $e) {
            return JsonResponse::handle(404, "Lịch đặt hẹn không tồn tại", null, 404);
        } catch (\Exception $e) {
            return JsonResponse::error(500, ConstantsMessage::ERROR, 500);
        }
    }
    
}

