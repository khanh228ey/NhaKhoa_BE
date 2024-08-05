<?php

namespace App\Http\Controllers\Client;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceClientResource;
use App\Models\Service;
use Exception;

class ServiceController extends Controller
{
    
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
