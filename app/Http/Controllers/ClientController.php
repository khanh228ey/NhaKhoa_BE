<?php

namespace App\Http\Controllers;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ServiceResource;
use App\Models\Category;
use App\Models\Schedule;
use App\Models\Schedule_time;
use App\Models\Service;
use App\Models\User;
use App\RequestValidations\AppointmentValidation;
use Exception;
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
                'education' => $item->education,
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


    public function getDoctorSchedule($id){
        $doctor = User::where('role_id',1)->find($id);
        $Schedule = Schedule::where('doctor_id', $id)
            ->select('date')->distinct()->orderBy('date', 'Asc')->get();
    
        // Tạo một mảng chứa các giá trị date
        $dates = $Schedule->pluck('date')->toArray();
    
        $result =  [
            'dates' => $dates,
        ];
    
        return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $result, 200);
    }
    

    public function getDoctorTimeslotsByDate($id, $date)
    {
        $schedule = Schedule::with('time')
            ->where('doctor_id', $id)
            ->where('date', $date)
            ->get();
    
        if ($schedule->isEmpty()) {
            return JsonResponse::handle(404, 'No timeslots found for this doctor on the given date', null, 404);
        }
    
        // Sử dụng map để lấy các giá trị time và gom chúng vào một mảng duy nhất
        $timeslots = $schedule->map(function ($item) {
            return $item->time->time;
        })->toArray();
    
        $result = [
            'times' => $timeslots,
        ];
    
        return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $result, 200);
    }
    
// đặt lịch
    Public function createAppointment(Request $request){
        $validator = $this->appointmentValidation->Appointment();
        if ($validator->fails()) {
            $firstError = $validator->messages()->first();
            return JsonResponse::handle(400,$firstError,$validator->messages(),400);
        }
        $appointment = $this->appointmentRepository->addAppointment($request->all());
        if ($appointment['success'] == true) {
            return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $appointment['appointment'], 200);     
        }
        return JsonResponse::error(401,$appointment['message'],401);
    }
/// Thời gian
        Public function getTime(){
                $time = Schedule_time::all();
                $timeslots = $time->map(function ($item) {
                    return [
                         $item->time,
                    ];
                });
            
                return JsonResponse::handle(200, ConstantsMessage::SUCCESS,  $timeslots, 200);
        }
// Danh sách danh mục
        public function getCategories(Request $request)
        {
            $perPage = $request->get('limit', 10);
            $page = $request->get('page'); 
            $query = Category::where('status',1);
            if (!is_null($page)) {
                $data = $query->paginate($perPage, ['*'], 'page', $page);
                $category = $data->items();
            } else {
                $category = $query->get();
            }
            $result = $category->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'image' => $item->image,
                ];
            });
            return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $result, 200);
        }


        Public function categoryfindById($id){
            try {
                $category = Category::where('status',1)->findOrFail($id); 
                $result = new CategoryResource($category);
                return JsonResponse::handle(200, ConstantsMessage::SUCCESS,  $result, 200);
            } catch (Exception $e) {
                return JsonResponse::handle(404, ConstantsMessage::Not_Found, null, 404);
            }
        }

/// danh sach dich vu 
        public function getServices(Request $request)
        {
            $perPage = $request->get('limit', 10);
            $page = $request->get('page'); 
            $category = $request->get('category_id');   
            $query = Service::with('category')->where('status',1);
            if($category){
                $query->where('category_id',  $category);
            }
            if (!is_null($page)) {
                $data = $query->paginate($perPage, ['*'], 'page', $page);
                $service = $data->items();
            } else {
                $service = $query->get();
            }
            $result = ServiceResource::collection($service);
            return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $result, 200);
            
        }
        
        Public function serviceFindById($id){
            try {
                $service = Service::where('status',1)->findOrFail($id); 
                $result = new ServiceResource($service);
                return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $result, 200);
            } catch (Exception $e) {
                return JsonResponse::error(404, ConstantsMessage::Not_Found, 404);
            }
        }

}
