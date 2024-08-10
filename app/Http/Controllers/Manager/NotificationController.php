<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotiResource;
use App\Repositories\Manager\NotiRepository;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //

    protected $notifications;
    public function __construct(NotiRepository $notifications)
    {
        $this->notifications = $notifications; 
        // $this->middleware('check.role:3')->except('getCategories');
    }

    public function getNoti(Request $request){
        $limit = $request->input('limit', 10);  
        $page = $request->input('page', 1);   
        $noti = $this->notifications->getNotify($limit,$page);
       
        $result = NotiResource::collection($noti);
        return $result;
    }

    // public function createNotifi($data){
        
    // }

    public function updateNotification($id){
        $getNoti = $this->notifications->findById($id);
        $notiUpdate = $this->notifications->updateNoti($getNoti);
        return $notiUpdate;
    }
}
