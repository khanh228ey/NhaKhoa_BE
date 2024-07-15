<?php

namespace App\Http\Controllers;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ServiceClientResource;
use App\Http\Resources\ServiceResource;
use App\Models\Category;
use App\Models\Schedule;
use App\Models\Schedule_time;
use App\Models\Service;
use App\Models\User;
use App\RequestValidations\AppointmentValidation;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
                'avatar' => $item->avatar,
                'description' => $item->description,
            ];
        });
        return JsonResponse::handle(200,ConstantsMessage::SUCCESS,$result,200);
    }

    Public function getDoctorDetail($id){
        try{
            $doctor = User::where('role_id',1)->where('status',1)->findOrFail($id);
            $result = 
                [
                    'id' => $doctor->id,
                    'name' => $doctor->name,
                    'description' => $doctor->description,
                    'avatar' => $doctor->avatar,
                    'birthday' => $doctor->birthday,
                    'email' => $doctor->email,
                    'gender' => $doctor->gender,
                ];
        return JsonResponse::handle(200,ConstantsMessage::SUCCESS,$result,200);
        }catch(ModelNotFoundException $e){
            return JsonResponse::handle(200,"Không tìm thấy bác sĩ",null,200);
        }
        
    }

    
    public function getDoctorTimeslotsByDate($id, $date)
    {
        $schedule = Schedule::with('time')
            ->where('doctor_id', $id)
            ->where('date', $date)
            ->where('status', 1)
            ->get();
    
        if ($schedule->isEmpty()) {
            return JsonResponse::handle(404, 'Không có thời gian trong ngày', null, 404);
        }
    
        $timeslots = $schedule->map(function ($item) {
            return ['time' => $item->time->time];
        })->unique('time')->values()->toArray();
    
        return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $timeslots, 200);
    }
    
    
    public function getDoctorScheduleWithTimeslots($id) {
        try {
            $doctor = User::where('role_id', 1)->findOrFail($id);
    
            $schedules = Schedule::with('time')
                ->where('doctor_id', $id)
                ->where('status', 1)
                ->where('date', '>=', now()->toDateString()) 
                ->orderBy('date', 'Asc')
                ->get();
    
            if ($schedules->isEmpty()) {
                return JsonResponse::handle(404, 'Không tìm thấy lịch làm việc của bác sĩ', null, 404);
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
    

/// Thời gian
    public function getTime()
    {
        $time = Schedule_time::all();
        $timeslots = $time->map(function ($item) {
            return ['time' => $item->time];
        })->toArray();
        
        return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $timeslots, 200);
    }
// Danh sách danh mục
        public function getCategories(Request $request)
        {
            $perPage = $request->get('limit', 10);
            $page = $request->get('page'); 
            $query = Category::with('services')->where('status',1);
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
            $query = Service::with('category')->where('status',1)->orderBy('quantity_sold','DESC');
            if($category){
                $query->where('category_id',  $category);
            }
            if (!is_null($page)) {
                $data = $query->paginate($perPage, ['*'], 'page', $page);
                $service = $data->items();
            } else {
                $service = $query->get();
            }
            $result = ServiceClientResource::collection($service);
            return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $result, 200);
            
        }
        
        Public function serviceFindById($id){
            try {
                $service = Service::where('status',1)->findOrFail($id); 
                $result = new ServiceClientResource($service);
                return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $result, 200);
            } catch (Exception $e) {
                return JsonResponse::error(404, ConstantsMessage::Not_Found, 404);
            }
        }

}
