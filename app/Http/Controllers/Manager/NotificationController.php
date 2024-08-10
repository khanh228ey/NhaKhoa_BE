<?php

namespace App\Http\Controllers\Manager;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotiResource;
use App\Repositories\Manager\NotiRepository;
use Exception;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //

    protected $notifications;
    public function __construct(NotiRepository $notifications)
    {
        $this->notifications = $notifications; 
    }

    public function getNoti(Request $request){
        $limit = $request->input('limit', 10);  
        $page = $request->input('page', 1);   
        $noti = $this->notifications->getNotify($limit,$page);
        $result = NotiResource::collection($noti);
        return $result;
    }

    public function updateNotification($id){
        try{
            $getNoti = $this->notifications->findById($id);
            $notiUpdate = $this->notifications->updateNoti($getNoti);
            return JsonResponse::handle(200,ConstantsMessage::SUCCESS, $notiUpdate,200);
        }catch(Exception){
            return JsonResponse::handle(500,ConstantsMessage::ERROR,null,500);
        }
    }
}
