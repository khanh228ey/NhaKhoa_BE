<?php

namespace App\Http\Controllers;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Http\Resources\DeleteResource;
use App\Http\Resources\ServiceResource;
use App\Models\Appointment_detail;
use App\Models\Category;
use App\Models\History_detail;
use App\Models\Service;
use App\Repositories\ServiceRepository;
use App\RequestValidations\ServiceValidation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    
    protected $serviceRepository;
    protected $serviceValidation;
    public function __construct(ServiceValidation $serviceValidation, ServiceRepository $serviceRepository)
    {
        $this->serviceValidation = $serviceValidation;
        $this->serviceRepository = $serviceRepository; 
        // $this->middleware('check.role:3')->except('getServices');
    }
    public function getServices(Request $request)
    {
        $perPage = $request->get('limit', 10);
        $page = $request->get('page'); 
        $name = $request->get('name');
        $category = $request->get('category_id');
        $query = Service::with('category');
        if ($name) {
            $query->where('name', 'LIKE', "%{$name}%");
        }
        if ($category) {
            $query->where('category_id', 'LIKE', "%{$category}%");
        }
        if (!is_null($page)) {
            $data = $query->paginate($perPage, ['*'], 'page', $page);
            $service = collect($data->items());
        } else {
            $service = $query->get();
        }
        $result = ServiceResource::collection($service);
        return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $result, 200);
        
    }

    Public function createService(Request $request){
        $validator = $this->serviceValidation->Service();
        if ($validator->fails()) {
            $firstError = $validator->messages()->first();
            return JsonResponse::handle(400, $firstError,$validator->messages(),400);
        }
        $service = $this->serviceRepository->addService($request->all());
        if ($service == false) {
                return JsonResponse::error(401,ConstantsMessage::ERROR,401);
        }
        $service = new ServiceResource($service);
        return JsonResponse::handle(200, ConstantsMessage::Add, $service, 200);
    }
     
    Public function findById($id){
        try {
            $service = Service::findOrFail($id); 
            $result = new ServiceResource($service);
            return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $result, 200);
        } catch (ModelNotFoundException $e) {
            return JsonResponse::handle(404, "Dịch vụ không tồn tại",null, 404);
        }
    }

    Public function updateService(Request $request,$id){
        try{
            $service = Service::findOrFail($id);
            $data = $request->all();
            if( count($data) > 1){
                $validator = $this->serviceValidation->Service();
                if ($validator->fails()) {
                    $firstError = $validator->messages()->first();
                    return JsonResponse::handle(400,$firstError,$validator->messages(),400);
                }
            }
            $service = $this->serviceRepository->updateService($request,$service);
            $service = new ServiceResource($service);
            return JsonResponse::handle(200, ConstantsMessage::Update, $service, 200);
        } catch (ModelNotFoundException $e) {

            return JsonResponse::handle(404, "Dịch vụ không tồn tại", null, 404);

        } catch (\Exception $e) {

            return JsonResponse::error(500, ConstantsMessage::ERROR, 500);

        }
    }
    
    Public function deleteService($id){
        try {
                $service = Service::findOrFail($id); 
                $checkHistory = History_detail::where('service_id',$id)->first();
                $checkAppointment = Appointment_detail::where('service_id',$id)->first();
            if($checkHistory || $checkAppointment){
                    return JsonResponse::error(409, 'Ràng buộc khóa ngoại', 409);
            }
            $service->delete();
            $service = new DeleteResource($service);
            return JsonResponse::handle(200, ConstantsMessage::Delete,$service, 200);
        } catch (ModelNotFoundException $e) {
            return JsonResponse::handle(404, "Dịch vụ không tồn tại", null, 404);
        } catch (\Exception $e) {
        return JsonResponse::error(500, ConstantsMessage::ERROR, 500);
    }
    }    
}
