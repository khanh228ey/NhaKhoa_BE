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

    public function getNoti(){
        $noti = $this->notifications->getNotify();
        $result = NotiResource::collection($noti);
        return $result;
    }

    public function createNotifi($data){
        
    }
}
