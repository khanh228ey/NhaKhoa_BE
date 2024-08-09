<?php
namespace App\Repositories\Manager;

use App\Models\Notification;
use Carbon\Carbon;

class NotiRepository{



    Public function getNotify($limit,$page){
        $getNotifications = Notification::orderBy('created_at', 'DESC')
                                 ->paginate($limit, ['*'], 'page', $page);
        $notifications =  collect($getNotifications->items());              
        return $notifications;
    }
    Public function createNotiAppointment($appointment_id){
        $noti = new Notification();
        $noti->title = "Lịch hẹn";
        $noti->message = "đã đặt một lịch hẹn mới";
        $noti->appointment_id = $appointment_id;
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

