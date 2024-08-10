<?php
namespace App\Repositories\Manager;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class NotiRepository{



    Public function getNotify($limit,$page){
        $getNotifications = Notification::orderBy('created_at', 'DESC')
                                 ->paginate($limit, ['*'], 'page', $page);
        $notifications =  collect($getNotifications->items());   
        Log::info('Lịch hẹn đã được tạo :' . $notifications);         
        return $notifications;
    }

    Public function createNotiAppointment($appointment_id){
        $noti = new Notification();
        $noti->title = "Lịch hẹn";
        $noti->message = "đã đặt một lịch hẹn mới";
        $noti->url = "/lich-hen/".$appointment_id;
        $noti->created_at = Carbon::now('Asia/Ho_Chi_Minh');
        $noti->save();
        return $noti;
    }


    Public function updateNoti($notifications){
        $notifications->status = 0;
        $notifications->save();
        return $notifications;
    }



}

