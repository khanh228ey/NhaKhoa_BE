<?php

namespace App\Http\Controllers\Client;
use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\Translate\ServiceResources;
use App\Models\Service;
use App\Repositories\Client\ServiceRepository;
use Exception;

class ServiceController extends Controller
{
    protected $serviceRepo;
    public function __construct(ServiceRepository $service)
    {
        $this->serviceRepo = $service; 
    }
    public function getServices(Request $request,$lang)
    {
        $perPage = $request->get('limit', 10);
        $page = $request->get('page');
        $query =  $this->serviceRepo->getServices();
        $service = !is_null($page) ? $query->paginate($perPage, ['*'], 'page', $page) : $query->get();
        $result = ($lang == 'vi') ? ServiceResource::collection($service) : ServiceResources::collection($service);
        return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $result, 200);
    }
    
    Public function serviceFindById($lang,$id){
        try {
            $category = $this->serviceRepo->findById($id);
            if($category == false){
                return JsonResponse::handle(404, ConstantsMessage::Not_Found, null, 404);
            }
            $result = ($lang == 'vi') ? new ServiceResource($category)
             :new ServiceResources($category);
            return JsonResponse::handle(200, ConstantsMessage::SUCCESS,  $result, 200);
        } catch (Exception $e) {
            return JsonResponse::handle(404, ConstantsMessage::ERROR, null, 404);
        }
    }

}
